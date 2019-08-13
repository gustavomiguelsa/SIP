# This "bridge" script subscribes to messages inside the 6LoWPAN network using unencrypted MQTT
# and then publishes the messages securely to the MQTT broker in the AWS cloud using TLS/SSL

# You can easily change this script, in case you need to subscribe to topics from the AWS cloud 
# and publish them locally in the 6LoWPAN network.

# Libraries:
import paho.mqtt.client as mqtt
import ssl
import sys
import time
import datetime as dt

############ Please change these parameters for your own setup #########


# There is a Mosquitto server running locally on the Virtual Machine. 
# For the project, you should change this to the IPv6 server of the local MQTT broker:
LOCAL_MQTT_URL = "localhost"
# You may also try "test.mosquitto.org"

# A topic to get information from the Zolertia board(s) to AWS:	 
LOCAL_AWS_TOPIC = "test_thing"

# A topic to get information from AWS to the Zolertia board(s):	 
AWS_LOCAL_TOPIC = "test_thing"
#AWS_LOCAL_TOPIC = "thing/YYYYY"

# Address of the Cloud MQTT Broker:
#CLOUD_MQTT_URL = "a2x25u069ramir-ats.iot.us-east-2.amazonaws.com" 
#CERTIFICATE_AUTH_FILE = "/home/user/projecto_iot/iot_certs/AmazonRootCA1.pem"
#CERT_PEM_FILE = "/home/user/projecto_iot/iot_certs/0b3fb4f4cd-certificate.pem.crt"
#PRIVATE_KEY_FILE = "/home/user/projecto_iot/iot_certs/0b3fb4f4cd-private.pem.key"

CLOUD_MQTT_URL = "a2x25u069ramir-ats.iot.us-east-2.amazonaws.com"
CERTIFICATE_AUTH_FILE = "/home/user/Desktop/NotasLaboratoriais8/BridgePython/iot_certs3/AmazonRootCA1.pem"
CERT_PEM_FILE = "/home/user/Desktop/NotasLaboratoriais8/BridgePython/iot_certs3/0b3fb4f4cd-certificate.pem.crt"
PRIVATE_KEY_FILE = "/home/user/Desktop/NotasLaboratoriais8/BridgePython/iot_certs3/0b3fb4f4cd-private.pem.key"


#########################################################################

# Callback for initial local network connection:
def on_connect(local_client, userdata, flags, rc):
    print("Connected to local MQTT broker with result code " + str(rc))
    local_client.subscribe(LOCAL_AWS_TOPIC)
    print("Subscribed to Local->Cloud topic: " + LOCAL_AWS_TOPIC + "\n")

# Callback for received message in the local network:
def on_local_message(local_client, userdata, msg):
    year = dt.datetime.now().year
    month = dt.datetime.now().month
    day = dt.datetime.now().day
    hour = dt.datetime.now().hour
    minute = dt.datetime.now().minute
    second = dt.datetime.now().second
    new_msg = msg.payload + ",\"Timestamp\":\""+str(year)+"*"+str(month)+"*"+str(day)+"*"+str(hour)+"*"+str(minute)+"*"+str(second)+"\"}";
    #publish the exact same message on the MQTT broker in the cloud:
    print("Local -> Cloud: Topic [" + msg.topic + "]. Msg \""+str(new_msg)+"\"")
    cloud_client.publish(msg.topic,str(new_msg))

# Callback for received message in the cloud:
def on_cloud_message(cloud_client, userdata, msg):
    #publish the exact same message on the local MQTT broker:
    print("Cloud -> Local: Topic [" + msg.topic + "]. Msg \""+str(msg.payload)+"\"")
    local_client.publish(msg.topic,str(msg.payload))

#########################################################################

# 1st connect to the Cloud MQTT Client:
cloud_client=mqtt.Client() 
cloud_client.on_message = on_cloud_message

print("Connecting to the Cloud at " + CLOUD_MQTT_URL + "...")
cloud_client.tls_set(ca_certs=CERTIFICATE_AUTH_FILE, certfile=CERT_PEM_FILE, keyfile=PRIVATE_KEY_FILE, tls_version=ssl.PROTOCOL_TLSv1_2)
cloud_client.tls_insecure_set(False)
cloud_client.connect(CLOUD_MQTT_URL, 8883, 60)
print("Connected to the Cloud MQTT Broker.")


time.sleep(0.5)
#cloud_client.publish(LOCAL_AWS_TOPIC,"{ \"Temp\": "+ str(12) + ", \"Id\": 1772, \"Timestamp\": "+str(34)+" }")


cloud_client.subscribe(AWS_LOCAL_TOPIC)
print("Subscribed to Cloud->Local topic: " + AWS_LOCAL_TOPIC + "\n")


# Then conect to the Local MQTT Client:
local_client = mqtt.Client()
local_client.on_connect = on_connect
local_client.on_message = on_local_message

print("Connecting locally to " + LOCAL_MQTT_URL + "...")
local_client.connect(LOCAL_MQTT_URL, 1883, 60)

# If we had just 1 connection we coud use client.loop_forever()
# since we have two, we'll use a while loop this way:

time.sleep(0.5)

try:
   while True:
        local_client.loop_forever(0.01) # timeout of 0.01 secs (max 100Hz)
        cloud_client.loop_forever(0.01)

except (KeyboardInterrupt): #catch keyboard interrupts
        sys.exit()


# You can test by running on your VM:
# mosquitto_pub -h localhost -t zolertia/sensor_status -m "Publiquei esta msg localmente e a bridge fez forward para a cloud!"

# Also test publishing something in the cloud and check if you receive it locally with:
# mosquitto_sub -h localhost -t cloud/action


