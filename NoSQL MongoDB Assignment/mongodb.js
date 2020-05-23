//a : connect to server
//mongo -u cs45Student26 -p "aPassword" --host thekomanetskys.com --authenticationDatabase "admin";
//b : preparations:

import { deepStrictEqual } from "assert";

    // 1 : choose database
use cs45Student26;

    // 2 : drop proveiously unwanted collections
//db.Patient.drop(); //possible create a patient collection later too
db.Hospital.drop();

//c : create the hospital database
db.createCollection ("Hospital" ,{ 
    validator: {$jsonSchema: { 
        bsonType: "object", 
        required: ["H_ID", "H_Name"], 
        properties: { 
            H_ID: {
                bsonType: "number",
                description: "the unique required id of each hospital"
            },
            H_Name: { 
                bsonType: "string", 
                description: "required, name of hospital"
            }, 
            Address: { 
                bsonType: "string", 
                description: "location of the hospital"
            },
            Capacity: {
                bsonType: "number",
            },
            totalDoctors: {
                bsonType: "number", 
                description: "amount of doctors working at the hospital" 
            },
            patientsList: { 
                bsonType: ["array"],
                items: { 
                    bsonType: "object", 
                required: ["P_ID", "P_Name"], 
                properties: { 
                    P_ID: { 
                        bsonType: "number", 
                        description: "required, unique, 6 digits preferably"
                    }, 
                    P_Name: { 
                        bsonType: "string", 
                        description: "required, the name of the patient"
                    },
                    Ailment: {
                        bsonType: "string" 
                    },
                    AdmitDate: {
                        bsonType: "date", 
                        description: "date of admission" 
                    },
                    Balance: { 
                        bsonType: "double" 
                    } 	
                }
                
                }
            } 	
        }
    }} 
});

// d : insert one hospital document into the hospital collection
var toInsert = {H_ID: 100001, H_Name: "UCSF Medical Center", Address: "505 Parnassus Avenue, San Francisco, CA", Capacity: 5000, totalDoctors: 220, patientsList: []};
db.Hospital.insert(toInsert);
    //notice we define the patient list as an empty array

// e : create a unique index on the hospital ID field 
db.Hospital.createIndex({ "H_ID": 1 }, { unique: true });

// // f : insert 4 more hospital documents into the hospital collection
var h1 = {H_ID: 100002, H_Name: "Johns Hopkins Hosp.", Address: "1800 Orleans St, Baltimore, MD 21287", Capacity: 1258, totalDoctors: 56, patientsList: []};
var h2 = {H_ID: 100003, H_Name: "UCLA Medical Center", Address: "1225, 15th St, Los Angeles, CA", Capacity: 3500, totalDoctors: 150, patientsList: []};
var h3 = {H_ID: 100004, H_Name: "Cleveland Clinic", Address: "9500 Euclid Ave, Cleveland, OH", Capacity: 200, totalDoctors: 4, patientsList: []};
var h4 = {H_ID: 100005, H_Name: "Massachusetts Hosp.", Address: "55 Fruit St, Boston, MA 02114", Capacity: 12000, totalDoctors: 350, patientsList: []};
db.Hospital.insertMany([h1, h2, h3, h4]);

// // g : insert a hospital document with the same id as the first one, expect an error
var toInsert2 = {H_ID: 100001, H_Name: "Virginia Mason Hospital", Address: "925 Seneca St, Seattle, WA", Capacity: 128, totalDoctors: 13, patientsList: []};
db.Hospital.insert(toInsert2);

// // h : create a variable which holds a patient document 
var p1 = {P_ID: 100001, P_Name: "John Smith", Ailment: "COVID-19", AdmitDate: new Date(), Balance: 2000.00};

// i : update one of the hospitals' array with the new patient. use the update $push syntax
db.Hospital.update( {H_ID: 100001}, { $push: {patientsList: p1}});

// j : insert 12 more patient documents to different hospitals 
var p1 = {P_ID: 100001, P_Name: "John Smith", Ailment: "COVID-19", AdmitDate: new Date(), Balance: 2000.00};
var p2 = {P_ID: 100002, P_Name: "Marcel Miles", Ailment: "Crohn's Disease", AdmitDate: new Date(), Balance: 0.00};
var p3 = {P_ID: 100003, P_Name: "Janne Stein", Ailment: "Common Cold", AdmitDate: new Date(), Balance: -12345.99};
var p4 = {P_ID: 100004, P_Name: "Eric Bergman", Ailment: "COVID-19", AdmitDate: new Date(), Balance: 10.00};
var p5 = {P_ID: 100005, P_Name: "Cassie Bates", Ailment: "Ammnesia", AdmitDate: new Date(), Balance: 1766.67};
var p6 = {P_ID: 100006, P_Name: "Alex Ossman", Ailment: "Broken Right Leg", AdmitDate: new Date(), Balance: 2000.00};
var p7 = {P_ID: 100007, P_Name: "Brooke Powell", Ailment: "COVID-19", AdmitDate: new Date(), Balance: 0.00};
var p8 = {P_ID: 100008, P_Name: "James Bennett", Ailment: "COVID-19", AdmitDate: new Date(), Balance: 0.00};
var p9 = {P_ID: 100009, P_Name: "Kaylee America", Ailment: "Diabetes", AdmitDate: new Date(), Balance: 100.00};
var p10 = {P_ID: 100010, P_Name: "Caroline Jimenez", Ailment: "COVID-19", AdmitDate: new Date(), Balance: 2.00};
var p11 = {P_ID: 100011, P_Name: "Ahmad Abadi", Ailment: "Tuberculosis", AdmitDate: new Date(), Balance: -3.50};
var p12 = {P_ID: 100012, P_Name: "Juan Lopez", Ailment: "Level 4 Lung Cancer", AdmitDate: new Date(), Balance: 0.00};

    // 1 : at least two patients per hospital
    db.Hospital.update( {H_ID: 100001}, { $push: {patientsList: {$each: [p2,p3]} } } );
    db.Hospital.update( {H_ID: 100002}, { $push: {patientsList: {$each: [p4, p5]} } } );
    db.Hospital.update( {H_ID: 100003}, { $push: {patientsList: {$each: [p6, p7]} } } );
    db.Hospital.update( {H_ID: 100004}, { $push: {patientsList: {$each: [p8, p9]} } } );

    // 2 : at least three patients with balance > 0.00
    db.Hospital.update( {H_ID: 100005}, { $push: {patientsList: {$each: [p10, p11, p12]} } } );

// k : foreach loop - print hospital names
print("\n -------------- HOSPITAL NAMES -----------------");
db.Hospital.find().forEach(
    function(hosp) {
        print("Hospital Name: " + hosp.H_Name);
    }
); 

// l : foreach loop - print name and patient capacity of each hospital
print("\n ----------- HOSPITAL PATIENT CAPACITIES -------------");
db.Hospital.find().forEach(
    function(hosp) {
        print("Hospital Name: " + hosp.H_Name + "  \t|  Patient Capacity: " + hosp.Capacity);
    }
); 

// m : foreach loop - print names of hospitals with totalDoctors > 20
print("\n ------------ HOSPITAL W/ DOCTORS > 20 --------------");
db.Hospital.find().forEach(
    function(hosp) {
        if(hosp.totalDoctors > 20) {
            print("Hospital Name: " + hosp.H_Name + "  \t|  Total Doctors: " + hosp.totalDoctors);
        }
    }
); 

// n : NESTED foreach loop - print hospital name, and the names of patients in that hospital with account > 0.00
print("\n ------------ PATIENTS W/ BALANCE > 0.00 --------------");
db.Hospital.find().forEach(
    function(hosp) {
        hosp.patientsList.forEach(
            function(pat){
                if(pat.Balance > 0.00){
                    print("Hospital: " + hosp.H_Name + "  \t|  Patient: " + pat.P_Name + "  \t|  Balance: " + pat.Balance);
                }
            }
        );
    }
); 

// o : NESTED foreach loop - print hospital name, and the names of patienst in that hospital with account <= 0.00
print("\n ------------ PATIENTS W/ BALANCE <= 0.00 --------------");
db.Hospital.find().forEach(
    function(hosp) {
        hosp.patientsList.forEach(
            function(pat){
                if(pat.Balance <= 0.00){
                    print("Hospital: " + hosp.H_Name + "  \t|  Patient: " + pat.P_Name + "  \t|  Balance: " + pat.Balance);
                }
            }
        );
    }
);

    