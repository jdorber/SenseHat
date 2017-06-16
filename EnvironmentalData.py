# EnvironmentalData.py script
# Creates a db connection to MySQL and inserts data from Sense Hat sensors
# Creates UID as Primary Key and collects Temperature (adjusted to compensate for CPU temperature), Pressure and Humidity from SenseHat hardware

import MySQLdb
import uuid
import datetime
import time
import os

#Set any global Variables:

#Wait time in seconds between logging attempts
wait = 600

#Temperature Compensation
temp_adj = 1.5

#set up SenseHat
from sense_hat import SenseHat
sense = SenseHat()

while True:
    #Sense Hat values
    t = sense.get_temperature()
    p = sense.get_pressure()
    h = sense.get_humidity()
    #DateTimeStamp = datetime.datetime.now() --Now doing on the db table
    UID = uuid.uuid4() # create GUID for use in MySQL as Primary Key

    #Compensate for CPU Temperature affecting SenseHat temperature sensor
    cpu_temp = os.popen("vcgencmd measure_temp").readline()
    cpu_t = float(cpu_temp.replace("temp=","").replace("'C\n",""))

    #Do the actual adjustment for CPU temperatures
    t = t - ((cpu_t-t)/temp_adj)

    #round the values to 1 decimal place
    t = 1.8 * round(t, 1) +32
    p = round(p, 1)
    h = round(h, 1)


    #Try block to execute the SQL INSERT and commit or rollback
    try:
        db = MySQLdb.connect(host="localhost", user="your user", passwd="your pwd", db="SenseHat")

        #Create a cursor
        cur = db.cursor()

        #Set up the SQL string and arguments using Sense Hat adjusted data for the INSERT Values
        sql = "INSERT INTO EnvironmentalData(ID, Temperature, Pressure, Humidity) VALUES (%s, %s, %s, %s)"
        args = (UID, t, p, h)


        #Execute MySQL logging attempt
        cur.execute(sql, args)
        db.commit()
        #print "Success" --debugging

        #Clean up the db connections
        cur.close()
        db.close()

    except:
        db.rollback()
        #print "Failure" --debugging

        #Clean up the db connections
        cur.close()
        db.close()

    #Wait for next logging interval
    time.sleep(wait)
