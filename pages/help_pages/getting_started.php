<html lang="en">
    <head>
        <title>Getting Started</title>
        
        <link rel="icon" href="../../images/favicon.ico" type="image/x-icon"/>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <!-- Include bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>
        
        <!-- Include jquery -->
        <script
          src="https://unpkg.com/jquery@3.6.0/dist/jquery.min.js"
          integrity="sha384-vtXRMe3mGCbOeY7l30aIg8H9p3GdeSe4IFlP6G8JMa7o7lXvnz3GFKzPxzJdPfGK"
          crossorigin="anonymous"
        ></script>
        
        <script>
            $('#myModal').on('shown.bs.modal', function () {
                $('#myInput').trigger('focus')
            })
        </script>
    </head>
    
    <body>
        <div class="modal show" aria-modal="true" style="display:block">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content">
        
                    <div class="modal-header">
                        <h4 class="modal-title">Getting started</h4>
                    </div>
        
                    <div class="modal-body">
                        <ol>
                            <li>1. Set up an account</li>
                            <span><br />If you haven't already set up an account, please register now. 
                            Alternatively, if you already have an account, just login if you haven't done so already.<br /><br /></span>
                            
                            <li>2. Buy a HQ</li>
                            <span><br />Under the "Management" tab, select "HQ". 
                            Then click "Buy HQ" and select a location. 
                            Your HQ should now be set up.<br /><br /></span>
                            
                            <li>3. Buy an Aircraft</li>
                            <span><br />Under the "Management" tab, select "Buy Aircraft". 
                            Then select "buy" for an aircraft you can afford (as you are starting, you will most likely only have enough money for the first aircraft).<br /><br /></span>
                            
                            <li>4. Buy Fuel</li>
                            <span><br />Under the "Management" tab, select "Fuel & Catering". 
                            Next, select an amount of fuel you wish to buy. 
                            Finally click buy.<br /><br /></span>
                            
                            <li>5. Create a route</li>
                            <span><br />You should now be able to create a route. 
                            To do this, select the pin of the airport you wish to depart from. 
                            Under the airport "Options", select "Set Departure". 
                            Next select an aircraft you wish to fly with. 
                            Finally select the airport you wish to fly to. 
                            Make sure that your aircraft has enough fuel and range to your selected destination.<br /><br /></span>
                        </ol>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" onclick="window.location.href='../../index.php'">Back to Game</button>
                        <p style="align: right">Last updated: 24-09-2022</p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>