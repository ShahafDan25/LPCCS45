import mysql.connector
import getpass
import sys
import functionsScript #will allow the main script (this file) to access all functions in the funcstionsScript file
from mysql.connector import Error #import exception handling for python (?) - not sure, check later;

#user = getpass.getuser()
#print(user)
uid = input("USERNAME: ") #cin user ID NOTE: getuser() did not allow me to insert any input for the username, rather it took the default username off of my computer
#another NOTE: To use getuser, please uncomment line 7
#uid = getpass.getuser() 
pw = getpass.getpass("PASSWORD: ") #cin password
db = input("What database would you like to connect to?  >>  ")

# ------- CONNECT TO DATABASE -------- #
try:
    #define the connection
    c = mysql.connector.connect(host="thekomanetskys.com", port=33066, database = db, user = uid, password = pw)
    
    if c.is_connected():
        print("Connected Successfully!")
    else:
        print("Something went wrong... try again")

except Error as e: #CATCH ERROR (kinda like a try and catch block)
    print ("\n----------------------------\nError while connecting to Database\n", e)
    sys.exit("\n     >>     TERMINATING PROGRAM \n----------------------------\n Good Bye")

action = functionsScript.menu() #call the menu function from the funcstions script
while action != "x":
    #pass the connectionc to each function, for operation
    if action == "1":
        functionsScript.display_states(c)
    elif action == "2":
        functionsScript.display_senators(c)
    elif action == "3":
        functionsScript.display_bills(c)
    elif action == "4":
        functionsScript.bills_by_state(c)
    else:
        print("Not an option, choose from menu:\n")
    #call the menu print again, store in 'action' variable
    action = functionsScript.menu()
    
    #end of constructive menu's while loop
print("\nBYE! Have a great rest of your day, benign and be nice.\n")
#close connection:
c.close()

