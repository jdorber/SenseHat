# EnvironmentalData.py script
# Creates a database connection to SenseHat MySQL and inserts data from
# SenseHat instruments to table EnvironmentalData
# Creates UID as Primary Key and collects Temperature (adjusted for CPU temperature), Pressure and Humidity values
# DateTimeStamp is now created on the Table itself on INSERT

#Import everything needed
import MySQLdb
import uuid
import datetime
import time
import os

#Set any global Variables:

#Wait time in seconds between logging attempts
wait = 600

#Temperature Adjustement - the Temperature Sensor is on the Raspberry Pi so gives spurious values - too HOT!
temp_adj = 1.5

#set up the sense hat for data gathering from the instruments
from sense_hat import SenseHat
sense = SenseHat()

#set the local variables for each logging attempt
while True:
    #Sense Hat values
    t = sense.get_temperature()
    p = sense.get_pressure()
    h = sense.get_humidity()
    #DateTimeStamp = datetime.datetime.now() --Not needed now as doing in the database table itself
    UID = uuid.uuid4() # crfeate GUID for use in MySQL as Primary Key

    #Because the SenseHat is right on top of the Raspberry Pi we need to do some adjustments
    #This gets the CPU Temperature and uses this to adjust actual temperature values
    cpu_temp = os.popen("vcgencmd measure_temp").readline()

    #Chop out the text and cast to float so we can work with it
    cpu_t = float(cpu_temp.replace("temp=","").replace("'C\n",""))

    #Do the actual adjustment for CPU temperatures
    t = t - ((cpu_t-t)/temp_adj)

    #round the values to 1 decimal place
    t = 1.8 * round(t, 1) +32
    p = round(p, 1)
    h = round(h, 1)


    #Try block to execute the SQL INSERT and commit or rollback
    try:
        #Set the database connection - keeping it simple as secure dedicated db
        db = MySQLdb.connect(host="localhost", user="your user", passwd="your pwd", db="SenseHat")

        #Create a cursor to do actions on the database
        cur = db.cursor()

        #Set up the SQL string and arguments using Sense Hat adjusted data for the INSERT Values
        sql = "INSERT INTO EnvironmentalData(ID, Temperature, Pressure, Humidity) VALUES (%s, %s, %s, %s)"
        args = (UID, t, p, h)


        #Execute MySQL logging attempt
        cur.execute(sql, args)
        db.commit()
        #print "Success"

        #Clean up the db connections
        cur.close()
        db.close()

    except:
        db.rollback()
        #print "Failure"

        #Clean up the db connections
        cur.close()
        db.close()

    #Wait for configurable time before continuing the logging
    time.sleep(wait)
