<body>
  <script>

    window.fbAsyncInit = function() {
      FB.init({
        appId: "1391047930989266",
        xfbml: true,
        version: "v2.6"
      });

      FB.Event.subscribe('send_to_messenger', function(e) {
        // callback for events triggered by the plugin
        console.log(e);
        var eventName = e.event;
        var userHasAuth = eventName == 'opt_in';
        
        if(userHasAuth){
          window.alert('Hello world message sent to you.');
        }
      });
    };

    (function(d, s, id){
       var js, fjs = d.getElementsByTagName(s)[0];
       if (d.getElementById(id)) { return; }
       js = d.createElement(s); js.id = id;
       js.src = "//connect.facebook.net/en_US/sdk.js";
       fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
  </script>

  <div class="fb-send-to-messenger" 
    messenger_app_id="1391047930989266" 
    page_id="1582722098684919" 
    data-ref="lalala" 
    color="blue" 
    size="standard"
    enforce_login="true">
  </div>
</body>