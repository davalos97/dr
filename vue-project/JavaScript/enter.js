//add all the functions and remove the console logs.  

//should we pull like 20 messages from the db if we have more than 20 messages?
// and put them into the array?
// pull it in the form of a mysql query or just a api, mabey FASTapi. 

listA = []; //replace with DB entry
//then have a for loop to generate the messages 
// in the background while the person logs in


//event listener for the enter key
function enter() {
    let value = document.getElementById("mainText").value
    listA.push(value);
    value = '';
    //reset it for next text
    // console.log("enter");
    //mabey add a api to this. 
} 

//apend to the main table
function append() {
    console.log("append");
}

document.getElementById("mainText").addEventListener("keydown", function(event) {
    if (event.key === "Enter") {
        enter();
    }
});


 //let something = document.getElementById("profileLayout").addEventListener()
 