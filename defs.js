window.fbAsyncInit = function () {
    FB.init({
        appId: '299220656893010',
         status: true, // check login status
         cookie: true, // enable cookies to allow the server to access the session
         xfbml: true // parse XFBML
        });
}

function (d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {
        return;
    }
    js = d.createElement(s);
    js.id = id;
    js.src = "//connect.facebook.net/en_US/all.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

/***********************************************
   * Drop down menu w/ description- � Dynamic Drive (www.dynamicdrive.com)
    * This notice must stay intact for use
     * Visit http://www.dynamicdrive.com/ for full source code
      ***********************************************/

function init_fb() {
    FB.Event.subscribe('auth.authResponseChange', function (response) {
        // Here we specify what we do with the response anytime this event occurs. 
        if (response.status === 'connected') {
        // The response object is returned with a status field that lets the app know the current
        // login status of the person. In this case, we're handling the situation where they 
        // have logged in to the app.
            testAPI();
        } else if (response.status === 'not_authorized') {
        // In this case, the person is logged into Facebook, but not into the app, so we call
        // FB.login() to prompt them to do so. 
        // In real-life usage, you wouldn't want to immediately prompt someone to login 
        // like this, for two reasons:
        // (1) JavaScript created popup windows are blocked by most browsers unless they 
        // result from direct interaction from people using the app (such as a mouse click)
        // (2) it is a bad experience to be continually prompted to login upon page load.
            FB.login();
        } else {
        // In this case, the person is not logged into Facebook, so we call the login() 
        // function to prompt them to do so. Note that at this stage there is no indication
        // of whether they are logged into the app. If they aren't then they'll see the Login
        // dialog right after they log in to Facebook. 
        // The same caveats as above apply to the FB.login() call here.
            FB.login();
        }
    });
}

// Here we run a very simple test of the Graph API after login is successful. 
// This testAPI() function is only called in those cases. 
function testAPI() {
    console.log('Welcome!  Fetching your information.... ');
    FB.api('/me', function (response) {
    console.log('Good to see you, ' + response.name + '.');
    });
}




// Now, see 2) below for final customization step
var genr;

function displaydesc(which, descriptionarray, container) {
    if (document.getElementById) {
        document.getElementById(container).innerHTML = descriptionarray[which.selectedIndex];
    }
}

var assignAll = function(genre, time, d) {
    assigngenre(genre);
    assigntime(time);
    assigndate(d);
    $(".moviebox").empty();

    var ID;
    var loc;
    FB.api('/me', function (response) {
        ID = response.id;
        loc = response.location;
        });

    $.get('scrape.php', {
        genre: genr,
        user_location: loc,
        uid: ID
        })
    .done(function (data) {
        $(".moviebox").append(data);
        });
    $(".friendbox").empty();
    $.get('friendbox.php', {
        genre: genr
        })
    .done(function (data) {
        $(".friendbox").append(data);
        });
}

function assigngenre(what) {
    var selectedopt = what.options[what.selectedIndex];
    if (document.getElementById && selectedopt.getAttribute("target") == "newwin") {
        genr = selectedopt.value;
    }
}

function assigntime(what) {
    var time;
    var selectedopt = what.options[what.selectedIndex];
    if (document.getElementById && selectedopt.getAttribute("target") == "newwin") {
        time = selectedopt.value;
    }
}

function assigndate(what) {
    var date;
    var selectedopt = what.options[what.selectedIndex];
    if (document.getElementById && selectedopt.getAttribute("target") == "newwin") {
        date = selectedopt.value;
    }
}


//2) Call function displaydesc() for each drop down menu you have on the page
//   This function displays the initial description for the selected menu item
//   displaydesc(name of select menu, name of corresponding text array, ID of SPAN container tag):
//   Important: Remove the calls not in use (ie: 2nd line below if there's only 1 menu on your page)

displaydesc(document.form1.Genre, thetext1, 'textcontainer1');
displaydesc(document.form2.Time, thetext2, 'textcontainer1');
displaydesc(document.form3.Date, thetext3, 'textcontainer1');

function invite() {
    FB.ui({
        method: 'send',
        user_prompt_message: 'Hi! Join me for a movie today.',
        link: "http://www.google.com", 
    },
    function (response) {
        if (response && response.post_id) {
            alert('Post was published.');
        } else {
            alert('Post was not published.');
        }
    });
}

$(document).ready(function () {
    init_fb();
    });
