import sys

# ---------- FUNCTIONS ---------------- #
#1. function one: print menu, return menu choice
def menu():
    print("\n-----------------------------------------------")
    menuChoice = input("Choose one of the following:\n < - 1 - > Display States \n < - 2 - > Display Senators \n < - 3 - > Display All Bills \n < - 4 - > Display Bills by State \n < - x - > Exit Program \n")
    return menuChoice
    #-- end of menu function

#2. function two will display all states and their info
def display_states(c):
    cursor = c.cursor() #cursor are needd to execute queries
    sql = "SELECT stateID, stateName, AvgPopulation FROM State"
    try:
        cursor.execute(sql)	#execute query
        results = cursor.fetchall() #fetch the information from the returned data
    except Error as e:
        print("Data retrieval went wrong.\n    >>   THE ERROR: ", e, "\nPlease consider exiting the program. \n----------------------------\n")
        return
    
    print('{:>5}'.format("ID"), '{:>15}'.format("State Name"), '{:>20}'.format("Average Population"))
    print('{:>5}'.format("~~"), '{:>15}'.format("~~~~~~~~~~"), '{:>20}'.format("~~~~~~~~~~~~~~~~~~"))

    for row in results:
        #row[0] = stateID; #row[1] = stateName; #row[2] = AvgPopulation
            print('{:>5}'.format(row[0]), '{:>15}'.format(row[1]), '{:>20}'.format(row[2]))

    cursor.close()	#close the cursor

#3. function three will display all senators and their information
def display_senators(c):
    cursor = c.cursor() #cursor are needd to execute queries
    sql = "SELECT idSenator, LastName, FirstName, DOB, State_stateID FROM Senator"
    try:
        cursor.execute(sql)	#execute query
        results = cursor.fetchall() #fetch the information from the returned data
    except Error as e:
        print("Data retrieval went wrong.\n    >>   THE ERROR: ", e, "\nPlease consider exiting the program. \n----------------------------\n")
        return
    print('{:>10}'.format("ID"), '{:>11}'.format("First Name"), '{:>16}'.format("Last Name"), '{:>15}'.format("Date of Birth"), '{:>7}'.format("State"))
    print('{:>10}'.format("~~"), '{:>11}'.format("~~~~~~~~~~"), '{:>16}'.format("~~~~~~~~~"), '{:>15}'.format("~~~~~~~~~~~~~"), '{:>7}'.format("~~~~~"))

    for row in results:
        #row[0] = idSenator; #row[1] = LastName; #row[2] = FirstName, #row[4] = stateID, #row[3] = DOB
        print('{:>10}'.format(row[0]), '{:>11}'.format(row[2]), '{:>16}'.format(row[1]), '{:>15}'.format(row[3]), '{:>7}'.format(row[4]))

    cursor.close()	#close the cursor
    return

#4. function four will display all bills and their information
def display_bills(c):
    
    try:
        cursor = c.cursor() #cursor are needd to execute queries
    except Error as e:
        print("\n\n----------------------------\nError initiating query cursor. \n Going back to menu")
        return #instead of sys exit, we are just going to exit the program
    sql = "SELECT BillID, BillName, BillDescription, BillDate, SenatorID_FK FROM Bills"
    try:
        cursor.execute(sql)	#execute query
        results = cursor.fetchall() #fetch the information from the returned data
    except Error as e:
        print("Data retrieval went wrong.\n    >>   THE ERROR: ", e, "\nPlease consider exiting the program. \n----------------------------\n")
        return
    print("\n----------------------------\nWARNING:  >>   You may need to zoom in/out of your screen for the correct formatting. Apologies. \n----------------------------\n")
    print('{:>10}'.format("ID"), '{:>29}'.format("Bill Name"), '{:>150}'.format("Bill's Description"), '{:>19}'.format("Date of Bill"), '{:>17}'.format("Senator's ID"))
    print('{:>10}'.format("~~"), '{:>29}'.format("~~~~~~~~~"), '{:>150}'.format("~~~~~~~~~~~~~~~~~~"), '{:>19}'.format("~~~~~~~~~~~~"), '{:>17}'.format("~~~~~~~~~~~~"))

    for row in results:
        #row[0] = idSenator; #row[1] = BillName; #row[2] = BillDescription, #row[3] = BillDate, #row[4] = SenatorID_FK
        print('{:>10}'.format(row[0]), '{:>29}'.format(row[1]), '{:>150}'.format(row[2]), '{:>19}'.format(row[3]), '{:>17}'.format(row[4]))

    cursor.close()	#close the cursor
    return

#5. function will display a bill based on a selected state
def bills_by_state(c):
    #step 1) ask the user for the state
    state = input("Select a state to see its bills. Type 'list' to see available states.   >>   ")
    if state == "list":
        state = display_state_codes(c)
    #step 2) test existence
    cursor_test = c.cursor()
    
    sql_test = "SELECT * from State WHERE stateID = '" + state + "'" #concatination
    cursor_test.execute(sql_test)
    results_test = cursor_test.fetchall()
    if(len(results_test) == 0):
        print("The state ID you entered was not found in the database. \n going back to menu \n----------------------------\n")
        return
    cursor_test.close() #close query executer: done with testing

    #step 3) print information and data in a formatted way
    cursor = c.cursor() #cursor are needd to execute queries
    sql = "SELECT BillID, BillName, stateID, stateName FROM StateBillsView WHERE stateID = '" + state  + "'"#return info for a specific state
    try:
        cursor.execute(sql)	#execute query
        results = cursor.fetchall() #fetch the information from the returned data
    except Error as e:
        print("Data retrieval went wrong.\n    >>   THE ERROR: ", e, "\nPlease consider exiting the program. \n----------------------------\n")
        return # exit function
    if(len(results) == 0): #check if the state has even issues anything
        print("No Bills were issues for this state, Sorry. \n Going back to menu \n----------------------------\n")
        cursor.close()
        return #exit function
    print('{:>10}'.format("Bill ID"), '{:>32}'.format("Bill Name"), '{:>10}'.format("State ID"), '{:>20}'.format("State Name"), '{:>20}'.format("Senator's Last Name"), '{:>20}'.format("Senator's First Name"))
    print('{:>10}'.format("~~~~~~~"), '{:>32}'.format("~~~~~~~~~"), '{:>10}'.format("~~~~~~~~"), '{:>20}'.format("~~~~~~~~~~"), '{:>20}'.format("~~~~~~~~~~~~~~~~~~~"), '{:>20}'.format("~~~~~~~~~~~~~~~~~~~~"))

        #step 4) fetch the rest of the information and data
    for row in results:
        #row[0] = BillID; #row[1] = BillName; #row[2] = stateID, #row[3] = stateName
        print('{:>10}'.format(row[0]), '{:>32}'.format(row[1]), '{:>10}'.format(row[2]), '{:>20}'.format(row[3]), end = " ") #adding the 'end = " "' so it wont start a new line next time Im printing
        cursor_b = c.cursor() #open another cursor (b) for another query line #close and reopen every loop 
        sql_b = "SELECT FirstName, LastName FROM Senator WHERE idSenator IN (SELECT SenatorID_FK FROM Bills WHERE BillID = " + str(row[0]) + ")"
        try:
            cursor_b.execute(sql_b) #execute query
            results_b = cursor_b.fetchall() #dig the information
        except Error as e:
            print("Data retrieval went wrong.\n    >>   THE ERROR: ", e, "\nPlease consider exiting the program. \n----------------------------\n")
            return # exit function
        for row_b in results_b:
            print('{:>20}'.format(row_b[0]), '{:>20}'.format(row_b[1])) #should have only fetched and queried one line, so the loop is in the sense of better be safe than sorry kinda thing
        cursor_b.close() #close the second cursor, opena new one in the next time we need to print A LINE
    #done printing
    cursor.close()	#close the cursor, atfer ALL the printing
    return
    
def display_state_codes(c):
    cursor = c.cursor() #statement kinda
    sql = "SELECT stateID FROM State" #SELECT ALL state
    try:
        cursor.execute(sql)	#execute query
        results = cursor.fetchall() #fetch the information from the returned data
    except Error as e:
        print("Data retrieval went wrong.\n    >>   THE ERROR: ", e, "\nPlease consider exiting the program. \n----------------------------\n")
        return
    print("Here are the currently available states from the Database: \n")
    for row in results:
        print(row[0], ", ", end =  " ") # adding the ' end = " " ', allows the print function to remain in the same line
    # done printing states
    state = input ("\n\n So what state did you choose?")
    cursor.close() #close the cursor
    return state #end of function