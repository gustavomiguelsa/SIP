# ==============================================================================
# Lambda function created under the project for the course: Internet of Things
# 
# Authors: Bruno Ferreira, uc2014201123, bruno.ferreira@isr.uc.pt
#          Gustavo Assunção, uc2014197707, gustavo.assuncao@isr.uc.pt
# Departamento Engenharia Eletrotécnica e de Computadores, Universidade de Coimbra
# 2019-06-23
# ==============================================================================
import boto3
import json
from decimal import Decimal
from boto3.dynamodb.conditions import Key, Attr
from botocore.exceptions import ClientError

import datetime
from dateutil import tz
import calendar
# ==============================================================================
# Helper class to convert a DynamoDB item to JSON.
class DecimalEncoder(json.JSONEncoder):
    def default(self, o):
        if isinstance(o, decimal.Decimal):
            if o % 1 > 0:
                return float(o)
            else:
                return int(o)
        return super(DecimalEncoder, self).default(o)
# ===================== FUNCTIONS ==============================================
# Function responsible for returning true if the date passed as an argument is the last day of the month
def check_if_last_day_of_month(date):
    #  calendar.monthrange return a tuple (weekday of first day of the 
    #  month, number  
    #  of days in month)
    last_day_of_month = calendar.monthrange(date.year, date.month)[1]
    # here i check if date is last day of month
    if date == datetime.date(date.year, date.month, last_day_of_month):
        return True
    return False
# Function responsible for updating the statistics tables
def someoneParked(ttable1, ttable2, ttable3):
    print("The spot 1 is now occupied! Updating weekly, monthly and anually tables")
    # The spot is now occupied
    # Increment the tables: Weekly, Monthly, Annual
    
    response = ttable1.get_item(
        Key={
            'ID': 1
        }
    )
    item = response['Item']
    number_cars = item['Carros'] + 1

    ttable1.update_item(
      Key={
        'ID': 1
      },
      UpdateExpression='SET #v = :val1',
      ExpressionAttributeValues={
        ":val1": Decimal(number_cars)
      },
      ExpressionAttributeNames={
        "#v": "Carros"
      }
    )
    # Update the monthly table
    response = ttable2.get_item(
        Key={
            'ID': 1
        }
    )
    item = response['Item']
    number_cars = item['Carros'] + 1

    ttable2.update_item(
      Key={
        'ID': 1
      },
      UpdateExpression='SET #v = :val1',
      ExpressionAttributeValues={
        ":val1": Decimal(number_cars)
      },
      ExpressionAttributeNames={
        "#v": "Carros"
      }
    )
    
    
    response = ttable3.get_item(
        Key={
            'ID': 1
        }
    )
    item = response['Item']
    number_cars = item['Carros'] + 1

    ttable3.update_item(
      Key={
        'ID': 1
      },
      UpdateExpression='SET #v = :val1',
      ExpressionAttributeValues={
        ":val1": Decimal(number_cars)
      },
      ExpressionAttributeNames={
        "#v": "Carros"
      }
    )
# Function responsible for updating the current state of the passed Parking Spot
def updateSpot(Spot, nid, ttable):
   #Keep updating the table of current states
    ttable.update_item(
      Key={
        'ID': nid
      },
      UpdateExpression='SET #v = :val1',
      ExpressionAttributeValues={
        ":val1": Decimal(Spot)
      },
      ExpressionAttributeNames={
        "#v": "Value"
      }
    )
# Function responsible for updating the temperature
def updateTemp(Temperature, ttable):
    ttable.update_item(
      Key={
        'ID': 5
      },
      UpdateExpression='SET #v = :val1',
      ExpressionAttributeValues={
        ":val1": Temperature
      },
      ExpressionAttributeNames={
        "#v": "Value"
      }
    )
# Function responsible for updating the humidity       
def updateHum(Humidity, ttable):
    ttable.update_item(
      Key={
        'ID': 6
      },
      UpdateExpression='SET #v = :val1',
      ExpressionAttributeValues={
        ":val1": Humidity
      },
      ExpressionAttributeNames={
        "#v": "Value"
      }
    )
# Function responsible for updating the tables whenever it's the end of a day/week/month
def rollOver(ttable, num_entries):
    response = ttable.scan()
    for i in response['Items']:
        if(i['ID'] < num_entries):
            ttable.update_item(
              Key={
                'ID': (Decimal(i['ID']) + 1)
              },
              UpdateExpression='SET #v = :val1',
              ExpressionAttributeValues={
                ":val1": Decimal(i['Carros'])
              },
              ExpressionAttributeNames={
                "#v": "Carros"
              }
            )
    ttable.update_item(
      Key={
        'ID': 1
      },
      UpdateExpression='SET #v = :val1',
      ExpressionAttributeValues={
        ":val1": Decimal(0)
      },
      ExpressionAttributeNames={
        "#v": "Carros"
      }
    )

# ============================= MAIN ===========================================
def lambda_handler(event,context):
    dynamodb = boto3.resource('dynamodb')
    #table = dynamodb.Table('ParkingZolTable')
    ddb_event = json.loads(json.dumps(event), parse_float=Decimal)
    
    now = datetime.datetime.now()
    # ======== ONE METHOD OF LOCAL TIME
    now2 = datetime.datetime.now(datetime.timezone.utc)
    curr_time = now2.astimezone(tz.gettz('Europe/Lisbon'))
    print(curr_time)
    # End of the day!
    if(curr_time.hour == 23 and curr_time.minute > 54 and curr_time.minute <= 59 and curr_time.seconds >= 57):
        table = dynamodb.Table('Semanal')
        # Update the weekly table!
        rollOver(table, 7)
    # End of the week
    if(curr_time.hour == 23 and curr_time.minute > 54 and curr_time.minute <= 59 and curr_time.seconds >= 57 and curr_time.weekday() == 6):
        #Update the Monthly table
        table = dynamodb.Table('Mensal')
        rollOver(table, 4)
    # End of the month
    if(curr_time.hour == 23 and curr_time.minute > 54 and curr_time.minute <= 59 and curr_time.seconds >= 57 and curr_time.weekday() == 6 and check_if_last_day_of_month(curr_time)):
        #Update the anual table
        table = dynamodb.Table('Anual')
        rollOver(table, 12)
            
    else:
        '''
        Arrival of new values from spot 1 and spot 2
        '''
        #First let's check the table with the current state of the spots
        tableS = dynamodb.Table('Semanal')
        tableM = dynamodb.Table('Mensal')
        tableA = dynamodb.Table('Anual')
        if(int(ddb_event['ID']) == 1): # ID equals to 1 -> values come from the first module, which sends the state of the spot1 and spot2
            table = dynamodb.Table('State')
            # Values from the Zolertia module
            ID = int(ddb_event ['ID'])
            Spot1 = int(ddb_event ['Spot1']) 
            Spot2 = int(ddb_event ['Spot2'])
            
            # Get the last state of spot 1 and 2 from State table
            response = table.get_item(
                Key={
                    'ID': 1
                }
            )
            item = response['Item']
            last_state_S1 = item['Value']
            
            response = table.get_item(
                Key={
                    'ID': 2
                }
            )
            item = response['Item']
            last_state_S2 = item['Value']
            
            if(last_state_S1 == 0 and Spot1 == 1):
                print("The spot 1 is now occupied! Updating weekly, monthly and anually tables")
                someoneParked(tableS, tableM, tableA)
            
            if(last_state_S2 == 0 and Spot2 == 1):
                print("The spot 2 is now occupied! Updating weekly, monthly and anually tables")
                someoneParked(tableS, tableM, tableA)
            
            updateSpot(Spot1, 1, table)
            updateSpot(Spot2, 2, table)
            
        '''
        Arrival of new values from spot 3 and spot 4
        '''
        #First let's check the table with the current state of the spots
    
        if(int(ddb_event['ID']) == 2): # ID equals to 1 -> values come from the first module, which sends the state of the spot1 and spot2
            table = dynamodb.Table('State')
            # Values from the Zolertia module
            ID = int(ddb_event ['ID'])
            Spot3 = int(ddb_event ['Spot3']) 
            Spot4 = int(ddb_event ['Spot4'])
            
            # Get the last state of spot 1 and 2 from State table
            response = table.get_item(
                Key={
                    'ID': 3
                }
            )
            item = response['Item']
            last_state_S3 = item['Value']
            
            response = table.get_item(
                Key={
                    'ID': 4
                }
            )
            item = response['Item']
            last_state_S4 = item['Value']
            
            if(last_state_S3 == 0 and Spot3 == 1):
                print("The spot 3 is now occupied! Updating weekly, monthly and anually tables")
                someoneParked(tableS, tableM, tableA)
            
            if(last_state_S4 == 0 and Spot4 == 1):
                print("The spot 4 is now occupied! Updating weekly, monthly and anually tables")
                someoneParked(tableS, tableM, tableA)
            
            updateSpot(Spot3, 3, table)
            updateSpot(Spot4, 4, table)
    
    '''
    Arrival of new values for temperature and humidity
    '''
    if(int(ddb_event['ID']) == 3):
        table = dynamodb.Table('State')
        ID = int(ddb_event ['ID'])
        Temperature = int(ddb_event ['Temperature'])
        Humidity = int(ddb_event ['Humidity'])
        print("New Temperature: %d" % Temperature)
        print("New Humidity: %d" % Humidity)
        updateTemp(Temperature, table)
        updateHum(Humidity, table)