# usage: python client.py '{"host":"cloud.lindneo.com","port":22221}' Help
import pickle , os 
import socket,ssl
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
path=os.path.dirname(os.path.realpath(__file__))


params = [];
command = "";


def Ping(client,params=[]):
   client.send ('Ping')
   Pong =  client.recv ( 1024 )
   if ( Pong == "Pong" ):
      print "Connection Tested!"
      return 1 
   else:
      print "No Connection!"
      return 0

def Help(client,params=[]):
   client.send ('Help')
   print client.recv ( 1024 )
def ListCatalog(client,params=[]):
   client.send ('ListCatalog')
   print client.recv ( 1024 )
def DeleteFromCatalog(client,params=[]):
   print "Deleting from catalog..."
   client.send ('DeleteFromCatalog')
   print "message sent...."
   try:
      params[0]
   except Exception, e:
      print "DeleteFromCatalog:No file path specified"
      client.close()
      sys.exit(3)
      return
   else:
      response = client.recv ( 1024 )
      print "Response is"+response
      if response == 'DeleteFromCatalogStarted':
      	filepath=params[0]
	print filepath
	client.send(filepath)
	response = client.recv ( 1024 )
	if response == '1':
		return 1
	else:
		return 0


    
def UpdateCatalogList(client,params=[]):
    pass

def AddToCatalog(client,params=[]):
   client.send ('AddToCatalog')
   try:
      params[0]
   except Exception, e:
      print "AddToCatalog:No file path specified"
      client.close()
      sys.exit(3)
      return
   else:
      file_path=params[0]
   
      try:
         params[1]
      except Exception, e:
         print "AddToCatalog:No filename specified"
         client.close()
	 sys.exit(2)
         return
      else: 
         filename=params[1]

   #print "File sending %s" % file_path
   response = client.recv ( 1024 )

   #key = hashlib.sha256(filename).digest()[:32]
   #print str (key)
   #vi = hashlib.sha256(filename).digest()[:16]
   #print str ( vi)
   #encryptOBJ = AES.new(key, AES.MODE_CFB, vi)
   
   if ('Ready' == response ):
      #print "Client is Ready"
      client.send(filename)

   response = client.recv ( 1024 )
   if (filename == response ):
      #print "HandShaked"
      pass
   else:
      print "Wrong HandShake"
      client.close()
      sys.exit(1)	
      return

   readByte = open(file_path, "rb")
   data = readByte.read()
   #encodedData = base64.b64encode(data)
   
   #encryptedData = encryptOBJ.encrypt(encodedData)
   readByte.close()
   client.send(data)
   print "100"
   print "File sent as %s" % filename
   sys.exit(0)
def GetFileChuncked(client,params=[]):
    client.send ('GetFile')
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
        print client.recv (1024)
        filepath=params[1]
        print "FileRequested: %s " % filename

        client.send (filename)
        
        response = client.recv ( 1024 ) 
        print "Response: %s " % response
        if (response == "OK"):
          client.send("Ready")

          hash_var=client.recv (1024)#egemen
          print "Length and hash:%s" %hash_var#egemen
          client.send("0")#egemen

          createFile = open(filepath, "wb")   
          print "New file is to written: %s " % filepath
          k=0
          while True:
              data = client.recv(1024)
              k+=1
              if ( data == "Bitiriyoruz"): break 
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
        'UpdateCatalogList': UpdateCatalogList,
        'AddToCatalog': AddToCatalog,
        'ServeFileToReader': ServeFileToReader ,
        'GetFileChuncked': GetFileChuncked,
        'DeleteFromCatalog': DeleteFromCatalog

    }



# Here's our thread:
class ConnectionThread ( threading.Thread ):

   def run ( self ):
      #print "client basladi"
      # Connect to the server:
      client = socket.socket ( socket.AF_INET, socket.SOCK_STREAM )
      ssl_sock = ssl.wrap_socket(client,
                           ca_certs=path+"/cloud_cert.crt",
                           cert_reqs=ssl.CERT_REQUIRED,
                           suppress_ragged_eofs=False)
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
               ssl_sock.connect ( ( connection["host"], connection["port"] ) )
            except Exception, e:
               print e
               print "Not connected"
               return
            else:
               try:
                  sys.argv[2]
               except IndexError:
                  Ping(ssl_sock)   
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
                        methods[command](ssl_sock,params)




      # Close the connection
      ssl_sock.close()

# Let's spawn a few threads:
ConnectionThread().start()
