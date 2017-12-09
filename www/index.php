<?php

// Colors
// orange : #e7d739
// blue : #0046c8
// darkblue : #011b40

$gameUrl="https://dav.li/ldjam40/";

if(!isset($_GET["game"]) || $_GET["game"]!="demo"){

require 'twitch.php';

$provider = new TwitchProvider([
    'clientId'                => '<insert yours>',
    'clientSecret'            => '<insert yours>',
    'redirectUri'             => $gameUrl,
    'scopes'                  => ["user_read","chat_login"] 
]);

if (!isset($_GET['code'])) {
    $authorizationUrl = $provider->getAuthorizationUrl();
    $_SESSION['oauth2state'] = $provider->getState();
    ?>
    <html>

    <head>
        <title>Motion Only - Ludum Dare 40 - David Libeau</title>
        <link rel="icon" href="assets/favicon.png" type="image/png" />
        <link rel="stylesheet" href="style.css" />
    </head>

    <body>
        <div id="logo">
            <img src="assets/logo-MotionOnly.png" />
            <div id="emoteLogo">
                <img src="https://static-cdn.jtvnw.net/emoticons/v1/25/3.0" />
            </div>
        </div>

        <p id="playBtn">
            <a href="<?php echo(" $authorizationUrl "); ?>" data-infobox="Login on Twitch and play with your viewers. They will have to publish/spam emotes. PS: you can put your chat on 'Emote only' mode!">Streamer mode <img src="https://static-cdn.jtvnw.net/emoticons/v1/112290/2.0" /></a>
            <br/>
            <a href="?game=demo" data-infobox="Demo 'random' mode, without Twitch.">Demo mode <img src="https://static-cdn.jtvnw.net/emoticons/v1/25/2.0"/></a>
        </p>

        <p id="infoBox">
            Select a mode
        </p>

        <footer><a href="https://github.com/DavidLibeau/LDJAM40" target="_blank" title="More info">Motion Only</a> - CC-BY-SA <a href="https://davidlibeau.fr" target="_blank" title="Author">David Libeau</a> - Ludum Dare 40 (48h jam) - <i>This game and website are not affiliated with <a href="https://www.twitch.tv/" target="_blank">Twitch</a>. The emotes are property of Twitch or their respective owners.</i></footer>

        <script src="https://dav.li/jquery/3.1.1.min.js"></script>
        <script>
            var spawnSound = new Audio("assets/sound/MotionOnly-spawn-1.wav");
            spawnSound.volume = 0.2;

            $("#playBtn>a").mouseenter(function() {
                spawnSound.play();
                $("#infoBox").text($(this).data("infobox"));
            });

            // Emote list
            var emoteList;
            $.ajax({
                url: "<?php echo($gameUrl); ?>/assets/twitchemotes.json"
            }).done(function(data) {
                emoteList = data;
                for (var i = 0; i < Object.keys(emoteList).length; i++) {
                    $("#emoteLogo").append("<img src=\"https://static-cdn.jtvnw.net/emoticons/v1/" + emoteList[Object.keys(emoteList)[i]].id + "/3.0\" style=\"display: none;\"/>");
                }
            });

            setInterval(function() {
                $("#emoteLogo>*").hide();
                $("#emoteLogo>*:nth-child(" + Math.round(Math.random() * Object.keys(emoteList).length) + ")").show();
            }, 250);
        </script>
    </body>

    </html>
    <?php
    exit;

} elseif (empty($_GET['state']) || (isset($_SESSION['oauth2state']) && $_GET['state'] !== $_SESSION['oauth2state'])) {

    if (isset($_SESSION['oauth2state'])) {
        unset($_SESSION['oauth2state']);
    }
    
    header('Location: index.php');

} else {

    try {

        // Get an access token using authorization code grant.
        $accessToken = $provider->getAccessToken('authorization_code', [
            'code' => $_GET['code']
        ]);

        // Using the access token, get user profile
        $resourceOwner = $provider->getResourceOwner($accessToken);
        $user = $resourceOwner->toArray();

        setcookie("accessToken",$accessToken);
        setcookie("username",htmlspecialchars($user['display_name']));
        /*echo '<html><body><table>';
        echo '<tr><th>Access Token</th><td>' . htmlspecialchars($accessToken->getToken()) . '</td></tr>';
        echo '<tr><th>Refresh Token</th><td>' . htmlspecialchars($accessToken->getRefreshToken()) . '</td></tr>';
        echo '<tr><th>Username</th><td>' . htmlspecialchars($user['display_name']) . '</td></tr>';
        echo '<tr><th>Bio</th><td>' . htmlspecialchars($user['bio']) . '</td></tr>';        
        echo '<tr><th>Image</th><td><img src="' . htmlspecialchars($user['logo']) . '"></td></tr>';
        echo '</table>';
        */
        
    } catch (Exception $e) {
        header('Location: index.php');
    }
}
    
}
?>

<html>
<head>
    <title>Motion Only - Ludum Dare 40 - David Libeau</title>
    <script src="https://aframe.io/releases/0.7.0/aframe.min.js"></script>
    <script src="text-geometry-aframe.js"></script>
    <link rel="icon" href="assets/favicon.png" type="image/png" />
</head>

<body>
    <a-scene>
        <a-assets>
            <a-asset-item id="logo-obj" src="assets/logo.obj"></a-asset-item>
            <a-asset-item id="logo-mtl" src="assets/logo.mtl"></a-asset-item>
            <a-asset-item id="pointer-obj" src="assets/pointer.obj"></a-asset-item>
            <a-asset-item id="pointer-mtl" src="assets/pointer.mtl"></a-asset-item>
            <a-asset-item id="bebasNeue" src="assets/Bebas_Neue_Regular.typeface.json"></a-asset-item>
            <img id="grid" src="assets/grid.png">
            <!-- Sound
            <a-asset-item id="spawn1" src="assets/sound/MotionOnly-spawn-1.wav"></a-asset-item>
            <a-asset-item id="spawn2" src="assets/sound/MotionOnly-spawn-2.wav"></a-asset-item>
            <a-asset-item id="spawn3" src="assets/sound/MotionOnly-spawn-3.wav"></a-asset-item>
            <a-asset-item id="spawn4" src="assets/sound/MotionOnly-spawn-4.wav"></a-asset-item>
            <a-asset-item id="spawn5" src="assets/sound/MotionOnly-spawn-5.wav"></a-asset-item>
            <a-asset-item id="spawn6" src="assets/sound/MotionOnly-spawn-6.wav"></a-asset-item>
            <a-asset-item id="spawn7" src="assets/sound/MotionOnly-spawn-7.wav"></a-asset-item>
            <a-asset-item id="spawn8" src="assets/sound/MotionOnly-spawn-8.wav"></a-asset-item>
            <a-asset-item id="spawn9" src="assets/sound/MotionOnly-spawn-9.wav"></a-asset-item>
            <a-asset-item id="point1" src="assets/sound/MotionOnly-point-1.wav"></a-asset-item>
            <a-asset-item id="point2" src="assets/sound/MotionOnly-point-2.wav"></a-asset-item>
            <a-asset-item id="point3" src="assets/sound/MotionOnly-point-3.wav"></a-asset-item>
            <a-asset-item id="point4" src="assets/sound/MotionOnly-point-4.wav"></a-asset-item>
            <a-asset-item id="point5" src="assets/sound/MotionOnly-point-5.wav"></a-asset-item>
            <a-asset-item id="loopmusic" src="assets/sound/MotionOnly-loop.mp3" preload="auto"></a-asset-item> -->
        </a-assets>
        
        <!-- Camera & pointer -->
        <a-entity position="0 2 -2">
            <a-entity camera look-controls>
                <a-entity position="0 0 -1.1" geometry="primitive:ring;radiusInner:0.2;radiusOuter:0.3" material="opacity:0;color:#e7d739;shader:flat" visible="false" cursor="fuse:true;fuseTimeout:10" raycaster="">
                </a-entity>
                <a-entity id="pointer" position="0 -0.05 -1" obj-model="mtl:#pointer-mtl;obj:#pointer-obj" rotation="0 -90 0" cursor="fuse:true;fuseTimeout:10" scale="0.2 0.2 0.2" visible="false">
                    <a-animation attribute="visible" to="true" delay="8000"></a-animation>
                    <a-entity id="score" text-geometry="value:0;height:0.2;size:0.3;font:#bebasNeue" material="opacity:0.3;color:#0046c8" opacity="0.5" position="0.01 1.08 0.93" rotation="0 90 0"></a-entity>
                    <a-entity id="timeLeft" text-geometry="value:60;height:0.2;size:0.3;font:#bebasNeue" material="opacity:0.3;color:#0046c8" opacity="0.5" position="0.01 -0.887 0.93" rotation="0 90 0"></a-entity>
                    <a-entity position="0.8 0.25 0" light="color:#fff;intensity:0.5;type:point"></a-entity>
                    </a-box>
                </a-entity>
            </a-entity>
        </a-entity>

        <a-entity class="3dnumber" text-geometry="height:0.4;value:3;size:2;font:#bebasNeue" material="color:#e7d739" visible="false">
            <a-animation attribute="visible" to="true"></a-animation>
            <a-animation attribute="position" from="-0.5 -2 -5" to="-0.5 0.8 -5" dur="1000" fill="both"></a-animation>
            <a-animation attribute="position" from="-0.5 0.8 -5" to="-0.5 -2 -5" dur="1000" delay="1000" fill="both"></a-animation>
            <a-animation attribute="visible" to="false" delay="2000"></a-animation>
        </a-entity>
        <a-entity class="3dnumber" text-geometry="height:0.4;value:2;size:2;font:#bebasNeue" material="color:#e7d739" visible="false">
            <a-animation attribute="visible" to="true" delay="2000"></a-animation>
            <a-animation attribute="position" from="-0.5 -2 -5" to="-0.5 0.8 -5" dur="1000" delay="2000" fill="both"></a-animation>
            <a-animation attribute="position" from="-0.5 0.8 -5" to="-0.5 -2 -5" dur="1000" delay="3000" fill="both"></a-animation>
            <a-animation attribute="visible" to="false" delay="4000"></a-animation>
        </a-entity>
        <a-entity class="3dnumber" text-geometry="height:0.4;value:1;size:2;font:#bebasNeue" material="color:#e7d739" visible="false">
            <a-animation attribute="visible" to="true" delay="4000"></a-animation>
            <a-animation attribute="position" from="-0.5 -2 -5" to="-0.5 0.8 -5" dur="1000" delay="4000" fill="both"></a-animation>
            <a-animation attribute="position" from="-0.5 0.8 -5" to="-0.5 -2 -5" dur="1000" delay="5000" fill="both"></a-animation>
            <a-animation attribute="visible" to="false" delay="6000"></a-animation>
        </a-entity>

        <!-- Logo -->
        <a-entity obj-model="mtl:#logo-mtl;obj:#logo-obj" rotation="0 -90 0" visible="false">
            <a-animation attribute="visible" to="true" delay="5500"></a-animation>
            <a-animation attribute="position" from="0 -3 -5" to="0 2 -5" dur="1500" delay="5500" fill="both"></a-animation>
            <a-animation attribute="position" from="0 2 -5" to="0 -3 -5" dur="1500" delay="7000" fill="both"></a-animation>
            <a-animation attribute="visible" to="false" delay="8500"></a-animation>
            <a-box position="-2.097 0.432 -1.646" rotation="0 -90 0" width="4" height="4" shadow="" material="transparent:true;color:#011b40" geometry="depth:0.6;height:3.72;width:11.27">
                <a-entity position="5.375 1.058 -2.383" light="color:#fff;intensity:0.5;type:point"></a-entity>
            </a-box>
        </a-entity>

        <!-- Score final -->
        <a-entity id="scoreFinal" visible="false" position="-1.6 0.5 -5">
            <a-entity text-geometry="value:Score;size:0.6;height:0.4;font:#bebasNeue" material="color:#e7d739" position="0 2.07 0">
            </a-entity>
            <a-entity id="scoreNb" text-geometry="value:0;font:#bebasNeue;height:0.4;size:2" material="color:#e7d739">
            </a-entity>
        </a-entity>

        <!-- Ground -->
        <a-entity geometry="primitive: plane; width: 10000; height: 10000;" rotation="-90 0 0" material="src: #grid; repeat: 10000 10000; transparent: true; metalness:0.6; roughness: 0.4;"></a-entity>
        <!-- Chemin -->
        <a-box position="0 0.2 -100" rotation="-90 0 0" width="4" height="4" shadow="" material="color:#0046c8; metalness:0.3" geometry="height:202;depth:0.4"></a-box>
        <!-- Socle -->
        <!--a-box position="0 0.2 -5" rotation="-90 0 0" width="4" height="4" shadow="" material="opacity:0.6; transparent:true; color:#e7d739" geometry="depth:0.6;height:2;width:4.5"></a-box-->
        <!-- Back -->
        <a-box position="0 3 2.55" rotation="-90 0 0" width="4" height="4" shadow="" material="color:#011b40; metalness:0.6" geometry="width:1000;depth:6;height:5"></a-box>

        <!-- Stars -->
        <a-entity id="stars">
            <a-sphere position="0 76 -148" material="emissive:#ffffff" geometry="radius:0.5"></a-sphere>
            <a-sphere position="40 130 -160" material="emissive:#ffffff" geometry="radius:0.5"></a-sphere>
            <a-sphere position="60 50 -110" material="emissive:#ffffff" geometry="radius:0.5"></a-sphere>
            <a-sphere position="40 50 -180" material="emissive:#ffffff" geometry="radius:0.5"></a-sphere>
            <a-sphere position="130 30 -10" material="emissive:#ffffff" geometry="radius:0.5"></a-sphere>
            <a-sphere position="130 90 -10" material="emissive:#ffffff" geometry="radius:0.5"></a-sphere>
            <a-sphere position="100 40 -50" material="emissive:#ffffff" geometry="radius:0.5"></a-sphere>
            <a-sphere position="120 130 -90" material="emissive:#ffffff" geometry="radius:0.5"></a-sphere>
            <a-sphere position="10 170 -10" material="emissive:#ffffff" geometry="radius:0.5"></a-sphere>
            <a-sphere position="30 150 -45" material="emissive:#ffffff" geometry="radius:0.5"></a-sphere>
            <a-sphere position="140 190 -29" material="emissive:#ffffff" geometry="radius:0.5"></a-sphere>
            <a-sphere position="-150 30 -10" material="emissive:#ffffff" geometry="radius:0.5"></a-sphere>
            <a-sphere position="-170 60 -90" material="emissive:#ffffff" geometry="radius:0.5"></a-sphere>
            <a-sphere position="-120 130 -90" material="emissive:#ffffff" geometry="radius:0.5"></a-sphere>
            <a-sphere position="-60 40 -160" material="emissive:#ffffff" geometry="radius:0.5"></a-sphere>
            <a-sphere position="-60 190 -160" material="emissive:#ffffff" geometry="radius:0.5"></a-sphere>
            <a-sphere position="-100 120 -160" material="emissive:#ffffff" geometry="radius:0.5"></a-sphere>
            <a-sphere position="-90 150 -5" material="emissive:#ffffff" geometry="radius:0.5"></a-sphere>
        </a-entity>

        <a-entity position="0 6 -4" light="color: #fff; intensity: 1.5; type: point"></a-entity>
        <a-sky color="#011b40"></a-sky>
    </a-scene>
    
    <script src="https://dav.li/jquery/3.1.1.min.js"></script>
    <script>
        function getCookie(cname) {
            var name = cname + "=";
            var decodedCookie = decodeURIComponent(document.cookie);
            var ca = decodedCookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }

        //Game var
        var gameStarted = false;
        var score = 0;
        var timeLeft = 60;

        var ascene = document.querySelector('a-scene');

        // Sounds
        var spawnSound = [new Audio("assets/sound/MotionOnly-spawn-1.wav"), new Audio("assets/sound/MotionOnly-spawn-2.wav"), new Audio("assets/sound/MotionOnly-spawn-3.wav"), new Audio("assets/sound/MotionOnly-spawn-4.wav"), new Audio("assets/sound/MotionOnly-spawn-5.wav"), new Audio("assets/sound/MotionOnly-spawn-6.wav"), new Audio("assets/sound/MotionOnly-spawn-7.wav"), new Audio("assets/sound/MotionOnly-spawn-8.wav"), new Audio("assets/sound/MotionOnly-spawn-9.wav")];
        for (sound in spawnSound) {
            spawnSound[sound].volume = 0.2;
        }
        var pointSound = [new Audio("assets/sound/MotionOnly-point-1.wav"), new Audio("assets/sound/MotionOnly-point-2.wav"), new Audio("assets/sound/MotionOnly-point-3.wav"), new Audio("assets/sound/MotionOnly-point-4.wav"), new Audio("assets/sound/MotionOnly-point-5.wav")];
        for (sound in pointSound) {
            pointSound[sound].volume = 0.2;
        }

        <?php if(!isset($_GET["game"]) || $_GET["game"]!="demo"){ ?>

        var accessToken = getCookie("accessToken");
        var username = getCookie("username");
        console.log("Connected as : " + username);

        var twitchserver = new WebSocket('wss://irc-ws.chat.twitch.tv/:6667/irc')

        var auth = 'oauth:' + accessToken //include oauth:xxxx
        var channel = username //channel name
        var Fadetime = 3 //time for message to fade away (in seconds)
        var Fadedelay = 20 //time till message starts to fade (in seconds)

        twitchserver.onopen = function open() {
            twitchserver.send('CAP REQ :twitch.tv/tags twitch.tv/commands twitch.tv/membership');
            twitchserver.send('PASS ' + auth);
            twitchserver.send('NICK ' + username);
            twitchserver.send('JOIN #' + username.toLowerCase());
        }

        function getMessageContent(id, data) {
            data = data.split(";");
            for (var i = 0; i < data.length; i++) {
                if (data[i].split("=")[0] == id) {
                    return data[i].split("=")[1];
                }
            }
        }

        function pingpong(data) {
            if (data.indexOf("PING") == 0) {
                twitchserver.send('PONG :tmi.twitch.tv');
                console.log("PONG");
            }
        }

        twitchserver.onmessage = function(data) { // message from server
            //console.log(data.data);
            pingpong(data.data);

            var chatUsername = getMessageContent("display-name", data.data);
            var emotes = getMessageContent("emotes", data.data);
            if (emotes != undefined && gameStarted) {
                addEmote(emotes.split("/")[0].split(":")[0]);
            }
        }

        <?php } else { //demo mode ?>

        console.log("DEMO MODE");

        // Emote list
        var emoteList;
        $.ajax({
            url: "<?php echo($gameUrl); ?>/assets/twitchemotes.json"
        }).done(function(data) {
            emoteList = data;
            randomEmote();
        });

        function randomEmote() {
            if (gameStarted) {
                var randomEmoteRandom=Math.round(Math.random() * Object.keys(emoteList).length-1);
                //console.log(randomEmoteRandom);
                var randomEmoteEmote=Object.keys(emoteList)[randomEmoteRandom];
                //console.log(randomEmoteEmote);
                addEmote(emoteList[randomEmoteEmote].id);
            }
            setTimeout(randomEmote, Math.floor(Math.random() * 3000));
        }


        <?php } ?>

        function sendToTwitchChat(message) {
            if (typeof twitchserver !== 'undefined') {
                twitchserver.send('PRIVMSG #' + username.toLowerCase() + ' :' + "[Motion only] " + message);
            } else {
                console.log(message);
            }
        }

        function addEmote(emote) {
            var emoteSrc = "https://static-cdn.jtvnw.net/emoticons/v1/" + emote + "/2.0";

            var img = new Image();
            img.onload = function() {
                var aentity = document.createElement('a-entity');
                aentity.setAttribute("emote-manager", "");

                var alight = document.createElement('a-entity');
                alight.setAttribute("light", "color:#fff;intensity:.2;type:point");

                var acone = document.createElement('a-cone');
                acone.setAttribute("geometry", "height:0.5;radiusBottom:0.18");
                acone.setAttribute("rotation", "-90.012 0 0");
                acone.setAttribute("position", "0 0 -0.26");
                acone.setAttribute("material", "opacity:0.9;color:#e7d739");

                var aimage = document.createElement('a-image');
                aimage.setAttribute("src", emoteSrc);
                aimage.setAttribute("width", img.width);
                aimage.setAttribute("height", img.height);
                aimage.setAttribute("scale", "0.01 0.01 0.01");

                var aanimation = document.createElement('a-animation');
                var x = ((Math.random() * 40) - 20);
                var y = (Math.random() * 5) + 1;
                //aentity.setAttribute("position", x + " " + y + " -20");
                aanimation.setAttribute("attribute", "position");
                aanimation.setAttribute("from", x + " " + y + " -20");
                aanimation.setAttribute("to", x + " " + y + " 0");
                aanimation.setAttribute("dur", "15000");
                aanimation.setAttribute("easing", "linear");

                var asphere = document.createElement('a-sphere');
                asphere.setAttribute("radius", 2);
                asphere.setAttribute("material", "opacity:0");

                aentity.appendChild(aimage);
                aentity.appendChild(alight);
                aentity.appendChild(acone);
                aentity.appendChild(asphere);
                aentity.appendChild(aanimation);
                ascene.appendChild(aentity);
            }
            img.src = emoteSrc;
        }

        AFRAME.registerComponent('emote-manager', {
            init: function() {
                //console.log("New emote init");
                spawnSound[Math.round(Math.random() * 8)].play();
                this.el.addEventListener("fusing", function(evt) {
                    if (gameStarted) {
                        this.parentNode.removeChild(this);
                        score++;
                        pointSound[Math.round(Math.random() * 3)].play();
                        document.querySelector("#score").setAttribute("text-geometry", "value:" + score + ";height:0.2;size:0.3;font:#bebasNeue");
                    }
                });
            },
            tick: function() {
                if (gameStarted) {
                    //this.el.setAttribute("position", this.el.object3D.position.x + " " + this.el.object3D.position.y + " " + (this.el.object3D.position.z + 0.03))
                    //console.log(this.el.object3D.position.z);
                    if (this.el.object3D.position.z >= 0) {
                        this.el.parentNode.removeChild(this.el);
                        score--;
                        pointSound[4].play();
                        document.querySelector("#score").setAttribute("text-geometry", "value:" + score + ";height:0.2;size:0.3;font:#bebasNeue");
                    }
                } else {
                    this.el.parentNode.removeChild(this.el);
                }
            }
        });

        setTimeout(function() {
            sendToTwitchChat("3");
            pointSound[0].play();
        }, 1000);
        setTimeout(function() {
            sendToTwitchChat("2");
            pointSound[0].play();
        }, 3000);
        setTimeout(function() {
            sendToTwitchChat("1");
            pointSound[0].play();
        }, 5000);
        setTimeout(function() {
            gameStarted = true;
            pointSound[2].play();
            $("#musicloop")[0].play();
            console.log("Game started!");
            sendToTwitchChat("Game started! Kappa");
            var gameInterval = setInterval(function() {
                if (timeLeft == 1) {
                    $("#musicloop")[0].pause();
                    pointSound[3].volume = 0.4;
                    pointSound[3].play();
                    setTimeout(function() {
                        pointSound[2].volume = 0.4;
                        pointSound[2].play();
                    }, 500);
                    setTimeout(function() {
                        pointSound[0].volume = 0.4;
                        pointSound[0].play();
                    }, 600);
                }
                if (timeLeft > 0) {
                    //console.log(timeLeft);
                    timeLeft--;
                    document.querySelector("#timeLeft").setAttribute("text-geometry", "value:" + timeLeft + ";height:0.2;size:0.3;font:#bebasNeue");
                } else {
                    clearInterval(gameInterval);
                    gameStarted = false;
                    sendToTwitchChat("End of the game! HSWP");
                    console.log("Score : " + score);
                    document.querySelector("#pointer").setAttribute("visible", "false");
                    document.querySelector("#scoreFinal").setAttribute("visible", "true");
                    document.querySelector("#scoreNb").setAttribute("text-geometry", "value:" + score + ";height:0.4;size:2;font:#bebasNeue");

                }
            }, 1000);
        }, 8000);
    </script>

    <audio id="musicloop" loop>
        <source src="assets/sound/MotionOnly-loop.wav" type="audio/wav">
    </audio>
</body>

</html>