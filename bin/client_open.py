# usage: python client.py '{"host":"127.0.0.1","port":22221}' Help
import pickle
import socket
import threading
import sys
import json
import signal
import hashlib
from Crypto.Cipher import AES
from Crypto import Random

import base64
def signal_handler(signal, frame):
        print 'You pressed Ctrl+C!'
        sys.exit(0)

signal.signal(signal.SIGINT, signal_handler)



params = [];
command = "";


def Ping(client,params=[]):
   client.send ( pickle.dumps([ 'Ping' ]))
   Pong = pickle.loads ( client.recv ( 1024 ) )
   if ( Pong[0] == "Pong" ):
      print "Connection Tested!"
      return 1 
   else:
      print "No Connection!"
      return 0

def Help(client,params=[]):
   client.send ( pickle.dumps([ 'Help' ]))
   print pickle.loads ( client.recv ( 1024 ) )
def ListCatalog(client,params=[]):
   client.send ( pickle.dumps([ 'ListCatalog' ]))
   print pickle.loads ( client.recv ( 1024 ) )
def DeleteFromCatalog(client,params=[]):
    pass
def UpdateCatalogList(client,params=[]):
    pass

def AddToCatalog(client,params=[]):
   client.send ( pickle.dumps([ 'AddToCatalog' ]))
   try:
      params[0]
   except Exception, e:
      print "AddToCatalog:No file path specified"
      client.close()
      return
   else:
      file_path=params[0]
   
      try:
         params[1]
      except Exception, e:
         print "AddToCatalog:No filename specified"
         client.close()
         return
      else: 
         filename=params[1]

   print "File sending %s" % file_path
   response = pickle.loads(client.recv ( 1024 ))

   key = hashlib.sha256(filename).digest()[:32]
   print str (key)
   vi = hashlib.sha256(filename).digest()[:16]
   print str ( vi)
   encryptOBJ = AES.new(key, AES.MODE_CFB, vi)
   
   if ('Ready' == response[0] ):
      print "Client is Ready"
      client.send(pickle.dumps([filename]))

   response = pickle.loads(client.recv ( 1024 ))
   if (filename == response[0] ):
      print "HandShaked"
   else:
      print "Wrong HandShake"
      client.close()
      return

   readByte = open(file_path, "rb")
   data = readByte.read()
   encodedData = base64.b64encode(data)

   encryptedData = encryptOBJ.encrypt(encodedData)
   readByte.close()
   client.send(encryptedData)
   print "File sent as %s" % filename

def GetFile(client,params=[]):
    client.send ( pickle.dumps([ 'GetFile' ]))
    try:
      params[0]
    except Exception, e:
      print "GetFile:No filename specified"
      client.close()
      return
    else: 
      filename=params[0]
      try:
        params[1]
      except Exception, e:
        print "GetFile:No file path specified"
        client.close()
        return
      else:
        print pickle.loads(client.recv (1024))[0]
        filepath=params[1]
        print "FileRequested: %s " % filename

        client.send (pickle.dumps([filename]))
        
        response = client.recv ( 1024 ) 
        print "Response: %s " % response
        if (response == "OK"):
          client.send("Ready")

          createFile = open(filepath, "wb")   
          print "New file is to written: %s " % filepath
          k=0
          while True:
              data = client.recv(1024)
              k+=1
              if not data: break 
              createFile.write(data)
          print str( round (k * 1.00 / 1024 , 2 ) ) + "MB transferred"
          print "File written"
          createFile.close()
        

        


def ServeFileToReader(client,params=[]):
    pass
    
methods = {
        'Ping': Ping ,
        'Help': Help ,
        'ListCatalog': ListCatalog,
        'DeleteFromCatalog': DeleteFromCatalog,
        'UpdateCatalogList': UpdateCatalogList,
        'AddToCatalog': AddToCatalog,
        'ServeFileToReader': ServeFileToReader ,
        'GetFile': GetFile
    }



# Here's our thread:
class ConnectionThread ( threading.Thread ):

   def run ( self ):

      # Connect to the server:
      client = socket.socket ( socket.AF_INET, socket.SOCK_STREAM )

      try:
         sys.argv[1]
      except IndexError:
         print "No Server-Client Mentioned"
      else:
         try:
            connection = json.loads(sys.argv[1])
         except ValueError:
            print sys.argv[1]
         else:
            try:
               client.connect ( ( connection["host"], connection["port"] ) )
            except Exception, e:
               print e
               print "Not connected"
               return
            else:
               try:
                  sys.argv[2]
               except IndexError:
                  Ping(client)   
               else:
                  command = sys.argv[2]
                  try:
                     sys.argv[3:]
                  except IndexError:
                     print "No Parameters Included"
                  else:
                     params = sys.argv[3:]

                     try:
                        methods[command]
                     except Exception, e:
                        print "No Method Found %s" % command
                     else:
                        methods[command](client,params)




      # Close the connection
      client.close()

# Let's spawn a few threads:
ConnectionThread().start()