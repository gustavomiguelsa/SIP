# SIP
Smart Indoor Parking System based on Internet of Things concepts.

This project used 3 Zolertia RE-Mote boards along with proximity sensors and a Temperatura & Humidity Sensor to monitor a small indoor garage mockup. The idea of this project was to demonstrate how IoT is a useful tool for developing neat systems which may help people in their everyday lives. Essentially, the goal of the system was to inform a user about which spaces were vacant, while also providing valuable statistical information.

Data was gathered by the RE-Mote boards and sent to a border router (here simulated using TunSlip), which forwarded it to a MQTT broker. This component would then send the data, after encryption and pre-processing to the AWS cloud services. There, further processing was performed through a lambda function and the results were stored in DynamoDB. As a frontend for the users a very simple and user-friendly website was developed.

Feel free to gather some ideas from this project if you feel like it.

Cheers!
