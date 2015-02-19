<!doctype html>
<!DOCTYPE html>
<html>
    <head>
        <title>Oferta</title>
        <link rel="stylesheet" href="/css/bootstrap.css"/>
        <script src="/js/jquery.js"></script>
        <script src="/js/bootstrap.min.js"></script>
        <style>
            .top5 { margin-top:5px; }
        </style>

    </head>
    <body   >

        <nav class="navbar navbar-default navbar-static-top" role="navigation">            
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="/">Auction : Cricket</a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">

                </div><!--/.nav-collapse -->
            </div>
        </nav>

        <div class="container">
            <div id="sidebar" class="col-md-4">
                <table class="table  table-bordered">
                    <tr>
                        <th colspan="4" class="text-center"><a href="auction.php?teamlist=1" target="_blank"> Team Listing </a></th>
                    </tr>
                    <tr>	
                        <th rowspan="2">
                            Captain Name 
                        </th>
                        <th colspan="3">
                            Bidding Values
                        </th>
                    </tr>
                    <tr>
                        <th>Available Balance</th>
                        <th>Maximum Bid Value</th>
                        <th>Players in team</th>
                    </tr>              
                    <?php foreach ($captain as $cap) {
                        ?>

                        <tr>
                            <td><?php echo $cap['name']; ?> </td>
                            <td><span id="current_bid_<?php echo $cap['id']; ?>"> 1100</span></td>
                            <td><span id="max_bid_allowed_<?php echo $cap['id']; ?>"><?php echo (1100/10); ?></span></td>
                            <td><span id="players_<?php echo $cap['id']; ?>">0</span></td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </div>
            <div id="content" class="col-md-8" >
                <!--  main content -->

                <div class="row top5" id="player" ng-controller="playerController">
                    <div class="col-md-12 top5">
                        <button class="btn btn-danger col-xs-3" ng-click="reset()" > Sold :: <span id="sold_pl"><?php echo $solded; ?></span></button>
                        <button class="btn btn-info col-xs-3" ng-click="reset()" > Unsold :: <span id="unsold_pl"><?php echo $unsold; ?> </span></button>
                        <button class="btn btn-default col-xs-3" ng-click="reset()" > To be Auctioned :: <span id="tobesold_pl"><?php echo $tobesold; ?> </span></button>

                    </div>

                    <?php if (!empty($player)) { ?>
                        <div class="col-md-12 top5">
                            <button class="btn btn-success col-xs-9" ng-click="reset()" id="go"> Go </button>


                        </div>

                        <div class="col-md-6 row top5" >
                            <?php $user_name = explode('@', $player[0]['email_id']); ?>


                                                                                <!--img src="https://tp.internal.directi.com/pics/upload_pic/thumbnail_<%= user_name.first %>.jpg"/-->

                                                        <!--<img id = 'user_img' src="https://tp.internal.directi.com/pics/upload_pic/large_{{username}}.jpg"/>-->
                            <img id = 'user_img' src="https://tp.internal.directi.com/pics/upload_pic/large_<?php echo $user_name[0]; ?>.jpg" width="350" height="350"/>
                        </div>
                        <div class="col-md-5 top5" > 
                            <!--input type="hidden" name="user_id" id="user_id" value="{{players.id}}"/>

                            Name : <span id="pl_name">{{players.name}}</span>
                            <br/>                            
                            Department : <span id="pl_desc">{{players.description}}</span>
                            <br/>                            
                            Skills : <span id="pl_skills"> {{players.skills}}</span>
                            <br/>
                            Email : <span id="pl_email_id">{{players.email_id}}</span>
                            <br/>
                            Contact : <span id="pl_contact">{{players.contact}}</span>
                            <br/-->
                            <input type="hidden" name="user_id" id="user_id" value="<?php echo $player[0]['id']; ?>"/>

                            Name : <span id="pl_name"><?php echo $player[0]['name']; ?></span>
                            <br/>                            
                            Department : <span id="pl_desc"><?php echo $player[0]['description']; ?></span>
                            <br/>                            
                            Skills : <span id="pl_skills"> <?php echo $player[0]['skills']; ?></span>
                            <br/>
                            Email : <span id="pl_email_id"><?php echo $player[0]['email_id']; ?></span>
                            <br/>
                            Contact : <span id="pl_contact"><?php echo $player[0]['contact']; ?></span>
                            <br/>
                        </div>
                    </div>
                <?php } else { ?>
                    <div> All Players Sold</div>
                <?php } ?>

                <div class="row">

                    <div class="col-md-3">
                        <select id="user_captain_id" name="user_captain_id">
                            <?php foreach ($captain as $cap) {
                                ?>
                                <option value="<?php echo $cap['id']; ?>"><?php echo $cap['name']; ?></option>
                            <?php } ?>
                        </select>

                    </div>
                    <div class="col-md-3">
                        <input type="text" id="bid_value" name="bid_value"/>                        
                    </div>
                    <div class="col-md-6">
                        <a href="/auction/sold" data-remote="true" id="sold" class="btn btn-primary ">Sold</a> 
                        <input type="hidden" name="previous_user_id" id="previous_user_id" value="<?php echo $player[0]['id']; ?>"/>
                        <a href="/auction/previous" data-remote="true" id="previous" class="btn btn-success ">Previous</a>                        
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="/js/angular.js"></script>
<!--script>
                                function captainController($scope, $http) {

                                }
                                function playerController($scope) {
                                    alert('parent');
                                    $scope.players = <?php echo json_encode($player[0]); ?>;
                                    var user = ($scope.players.email_id).split('@');
                                    $scope.username = user[0];
                                    $scope.reset = function () {
                                        alert('reset called');
                                        $.ajax({
                                            type: "POST",
                                            data: 'ajax=1&action=get_player',
                                            success: function (message) {

                                                $scope.players = message;
                                                console.log($scope.players);
                                                console.log($scope.players['name']);
                                                var user = (message.email_id).split('@');
                                                $scope.username = user[0];
                                                angular.copy($scope.players);
                                            }
                                        });

                                    }
                                }
</script-->
<script>

$('#previous').click(function (event) {    
    event.preventDefault();
    var user_id = $('#previous_user_id').val();
    $.ajax({
        type: "POST",
        data: 'ajax=1&action=get_previous_player&user_id=' + user_id,
        success: function (message) { 

            var tmp_data = JSON.parse(message);
            var username = tmp_data[0]['email_id'].split('@');
            $('#user_img').attr('src', "https://tp.internal.directi.com/pics/upload_pic/large_" + username[0] + ".jpg");
            $('#user_id').val(tmp_data[0]['id']);
            $('#pl_name').text(tmp_data[0]['name']);
            $('#pl_desc').text(tmp_data[0]['description']);
            $('#pl_skills').text(tmp_data[0]['skills']);
            $('#pl_email_id').text(tmp_data[0]['email_id']);
            $('#pl_contact').text(tmp_data[0]['contact']);
            $('#previous_user_id').val(tmp_data[0]['previous_user_id']);
            $('#bid_value').value = '';
            
            if(tmp_data[0]['captain_id'] != 0){
                var captain_id = tmp_data[0]['captain_id'];

                var current_bid = $('#current_bid_' + captain_id).text();

                //reverse update 
                var ava_bid = parseFloat(current_bid) + parseFloat(tmp_data[0]['bid_value']);
                $('#current_bid_' + captain_id).text(ava_bid);
                var player = parseInt($('#players_' + captain_id).text()) - 1;
                $('#players_' + captain_id).text(player);
                var total_player_required = 10 - player;
                var curr_max_allowed = parseInt(ava_bid / total_player_required);                
                $('#max_bid_allowed_' + captain_id).text(curr_max_allowed);
            }
            
            get_count();
        },
        error: function () {
            alert('Unable to update data');
        }
    });
});

$('#go').click(function (event) {
    event.preventDefault();
    var user_id = $('#user_id').val();
    var prev_user_id = $('#previous_user_id').val();
    $.ajax({
        type: "POST",
        data: 'ajax=1&action=get_player&user_id=' + user_id + '&prev_user_id='+prev_user_id,
        success: function (message) {

            var tmp_data = JSON.parse(message);
            var username = tmp_data[0]['email_id'].split('@');
            $('#user_img').attr('src', "https://tp.internal.directi.com/pics/upload_pic/large_" + username[0] + ".jpg");
            $('#user_id').val(tmp_data[0]['id']);
            $('#pl_name').text(tmp_data[0]['name']);
            $('#pl_desc').text(tmp_data[0]['description']);
            $('#pl_skills').text(tmp_data[0]['skills']);
            $('#pl_email_id').text(tmp_data[0]['email_id']);
            $('#pl_contact').text(tmp_data[0]['contact']);
            $('#previous_user_id').val(user_id);
            get_count();
        },
        error: function () {
         alert('Unable to update data');
        }
    });
});

function get_count() {
    $.ajax({
        type: "POST",
        data: 'ajax=1&action=get_player_count',
        success: function (message) {

            var tmp_data = JSON.parse(message);
            console.log(tmp_data);
            $('#sold_pl').text(tmp_data['sold']);
            $('#unsold_pl').text(tmp_data['unsold']);
            $('#tobesold_pl').text(tmp_data['tobesold']);
        },
        error: function () {
            alert('Unable to update data');
        }
    });
}

$('#sold').click(function (event) {
    event.preventDefault();
    var user_id = $('#user_id').val();
    var prev_user_id = $('#previous_user_id').val();
    var captain_id = $('#user_captain_id').val();
    var bid_value = $('#bid_value').val();
    var data_value = {'ajax': 1, 'previous_user_id': prev_user_id,'user_id': user_id, 'captain_id': captain_id, 'bid_value': bid_value};
    var max_bid_allow = $('#max_bid_allowed_' + captain_id).text();
    var current_bid = $('#current_bid_' + captain_id).text();
        
    if (bid_value === '') {
        alert('put bid value');
    } else if (parseInt(bid_value) > parseInt(max_bid_allow)) {
        alert('Exceeds max allowed limit');
    } else {

        $.ajax({
            type: "POST",
            data: data_value,
            success: function (message) {

                var ava_bid = parseFloat(current_bid) - parseFloat(bid_value);
                $('#current_bid_' + captain_id).text(ava_bid);
                var player = parseInt($('#players_' + captain_id).text()) + 1;
                console.log(player);
                $('#players_' + captain_id).text(player);
                var total_player_required = 10 - player;
                var curr_max_allowed = parseInt(ava_bid / total_player_required);
                $('#max_bid_allowed_' + captain_id).text(curr_max_allowed);
//                                            $('#go').trigger('click');

                var tmp_data = JSON.parse(message);
                var username = tmp_data[0]['email_id'].split('@');
                $('#user_img').attr('src', "https://tp.internal.directi.com/pics/upload_pic/large_" + username[0] + ".jpg");
                $('#user_id').val(tmp_data[0]['id']);
                $('#pl_name').text(tmp_data[0]['name']);
                $('#pl_desc').text(tmp_data[0]['description']);
                $('#pl_skills').text(tmp_data[0]['skills']);
                $('#pl_email_id').text(tmp_data[0]['email_id']);
                $('#pl_contact').text(tmp_data[0]['contact']);
                $('#bid_value').value = '';
                $('#previous_user_id').val(user_id);
                get_count();
            },
            error: function () {
                alert('Unable to update data');
            }
        });
    }

});
</script>

</body>
</html>
