function populateclub(s0, s1) {
  var s0 = document.getElementById(s0);
  var s1 = document.getElementById(s1);
  s1.innerHTML = "";
  if (s0.value == "Hill") {
    var optionArray0 = ["|", "Aberdeen|Aberdeen", "Airworks|Airworks", "Avon|Avon", "Cayley|Cayley", "Condors|Condors", "Cumbria|Cumbria", "Dales|Dales", "Derbyshire|Derbyshire", "Dover and Folkestone|Dover and Folkestone", "East Anglia Coastal|East Anglia Coastal", "Flying Fever|Flying Fever", "Flying Frenzy|Flying Frenzy", "Highland|Highland", "Isle of Wight|Isle of Wight", "Kernow|Kernow", "Lanarkshire and Lothian|Lanarkshire and Lothian", "Long Mynd|Long Mynd", "Malvern|Malvern", "Mid Wales|Mid Wales", "North Devon|North Devon", "North Wales|North Wales", "North Yorks|North Yorks", "Northumbria|Northumbria", "Peak|Peak", "Pennine|Pennine", "Scotland - Pentlands|Scotland - Pentlands", "Scotland - SE Highlands|Scotland - SE Highlands", "Scotland - W Highlands|Scotland - W Highlands", "SE Wales|SE Wales", "Sky Surfing|Sky Surfing", "Snowdonia|Snowdonia", "South Devon|South Devon", "Southern|Southern", "SW Wales|SW Wales", "Thames Valley|Thames Valley", "Welsh Borders|Welsh Borders", "Wessex|Wessex", "Wingbeat|Wingbeat", "YX Paragliding|YX Paragliding"];
  } else if (s0.value == "Power") {
    var optionArray0 = ["|", "Airworks|Airworks", "Fat Paramotor|Fat Paramotor", "FlyPap|FlyPap", "Foot Flight Paramotors|Foot Flight Paramotors", "GPPG Flyers|GPPG Flyers", "Green Dragons|Green Dragons", "North Devon PPG|North Devon PPG", "Northern Skies|Northern Skies", "Rutland|Rutland", "Team Badgers|Team Badgers", "Ufly4fun|Ufly4fun"];
  } else if (s0.value == "Aerotow") {
    var optionArray0 = ["|", "Cambridgeshire|Cambridgeshire", "Malvern Aerotow|Malvern Aerotow", "Midland|Midland", "Upottery|Upottery"];
  } else if (s0.value == "Tow/Winch") {
    var optionArray0 = ["|", "Cloudbase|Cloudbase", "Norfolk|Norfolk", "Rutland|Rutland", "Suffolk|Suffolk", "XClent|XClent"];
  }
  for (var option0 in optionArray0) {
    var pair0 = optionArray0[option0].split("|");
    var newOption0 = document.createElement("option");
    newOption0.value = pair0[0];
    newOption0.innerHTML = pair0[1];
    s1.options.add(newOption0);
  }
}

function populateloc(s1, s2) {
  var s1 = document.getElementById(s1);
  var s2 = document.getElementById(s2);
  s2.innerHTML = "";
  if (s1.value == "Aberdeen") {
    var optionArray1 = ["|", "Ben Gulabin NO104722|Ben Gulabin", "Ben Rinnes NJ255355|Ben Rinnes", "Bin of Cullen NJ480643|Bin of Cullen", "Bridge of Don NJ955099|Bridge of Don", "Buck, Cabrach NJ412234|Buck, Cabrach", "Cairn O' Mount NO650805|Cairn O' Mount", "Cairn Veich NJ239103|Cairn Veich", "Cairnwell NO135773|Cairnwell", "Carn Liath, Blair Atholl NN936698|Carn Liath, Blair Atholl", "Coiliochbhar NJ507159|Coiliochbhar", "Craig nan Gabhar NO147837|Craig nan Gabhar", "Craigs of Lethnot NO363655|Craigs of Lethnot", "Creag an Eunan NJ384191|Creag an Eunan", "Crovie NJ807652|Crovie", "Cruden Bay NK088357|Cruden Bay", "Dunnydeer NJ612282|Dunnydeer", "Fourman Hill NJ574460|Fourman Hill", "Fraserburgh Dunes NK006656|Fraserburgh Dunes", "Gardenstown NJ790646|Gardenstown", "Kerloch NO697879|Kerloch", "Knock Hill NJ537552|Knock Hill", "Knock of Formal NO254546|Knock of Formal", "Lochindorb NH984360|Lochindorb", "Montrose Dunes NO727585|Montrose Dunes", "Morrone, Braemar NO132886|Morrone, Braemar", "Morven, Ballater NJ393042|Morven, Ballater", "Pressendye NJ490090|Pressendye", "Rattray Head NK105582|Rattray Head", "Scar Hill NJ486112|Scar Hill", "Sgor Gaoithe NJ074219|Sgor Gaoithe", "St Cyrus NO754647|St Cyrus", "Tap O' Noth NJ484293|Tap O' Noth", "The Bochel, Glenlivet NJ234233|The Bochel, Glenlivet", "Tom Dubh, Gairnshiel NJ311044|Tom Dubh, Gairnshiel", "Tops of Fichell NO381684|Tops of Fichell"]; 
  } else if (s1.value == "Airworks") {
    var optionArray1 = ["|", "Loose Bottom TQ372076|Loose Bottom", "Piltdown Airstrip TQ435221|Piltdown Airstrip"];
  } else if (s1.value == "Avon") {
    var optionArray1 = ["|", "Bratton Camp ST900516|Bratton Camp", "Cam Long Down ST773995|Cam Long Down", "Frocester SO794012|Frocester", "Long Knoll ST796377|Long Knoll", "Mere Coward's Bowl ST806345|Mere Coward's Bowl", "Mere Morgan's Ridge ST803354|Mere Morgan's Ridge", "Mere Rifle Range ST811344|Mere Rifle Range", "Mere Spencer's Bowl ST802350|Mere Spencer's Bowl", "Selsley Common SO827032|Selsley Common", "Westbury ST898515|Westbury"];
  } else if (s1.value == "Cambridgeshire") {
    var optionArray1 = ["Sutton Meadows TL404784|Sutton Meadows"];
  } else if (s1.value == "Cayley") {
    var optionArray1 = ["|", "Horcum SE846940|Horcum", "Millington SE834514|Millington"];
} else if (s1.value == "Cloudbase") {
  var optionArray1 = ["South Cerney SU055987|South Cerney"];
  } else if (s1.value == "Condors") {
    var optionArray1 = ["|", "Batcombe (Telegraph Hill) ST642051|Batcombe (Telegraph Hill)", "Beer Head SY227878|Beer Head", "Bossington SS903485|Bossington", "Countisbury SS748501|Countisbury", "East Hill SY112926|East Hill", "Eype SY451916|Eype", "North Hill SS952478|North Hill", "Selworthy SS904489|Selworthy"];
  } else if (s1.value == "Cumbria") {
    var optionArray1 = ["|", "Barkin Fell SD675870|Barkin Fell", "Barton Fell NY463212|Barton Fell", "Bewaldeth NY211362|Bewaldeth", "Black Combe (East) SD149850|Black Combe (East)", "Black Combe (South) SD131828|Black Combe (South)",
      "Black Combe (West) SD119862|Black Combe (West)", "Bleansley SD204895|Bleansley", "Blease Fell NY306261|Blease Fell", "Brigsteer SD488892|Brigsteer", "Burlington SD251844|Burlington", "Burnbank NY112213|Burnbank",
      "Burney Fell SD259855|Burney Fell", "Buttermere Moss NY187169|Buttermere Moss", "Carrock Fell NY348338|Carrock Fell", "Clough Head NY328231|Clough Head", "Coniston Old Man SD276974|Coniston Old Man", "Corney Fell SD138920|Corney Fell",
      "Ellerside SD354805|Ellerside", "Farleton Knott SD538804|Farleton Knott", "Great Langdale NY285073|Great Langdale", "Haig Pit NX965177|Haig Pit", "Humphrey Head SD392735|Humphrey Head", "Jenkin Hill NY279264|Jenkin Hill",
      "Latrigg NY277246|Latrigg", "Lowca NX980215|Lowca", "Murton Pike NY729219|Murton Pike", "Rebecca SD230784|Rebecca", "Sandbeds/West Fell NY326364|Sandbeds/West Fell", "Silecroft SD118815|Silecroft", "Souther Fell NY355291|Souther Fell",
      "St. Bees NX962117|St. Bees", "Swinside NY174243|Swinside", "Troutbeck NY423032|Troutbeck", "Ullock Pike NY240304|Ullock Pike", "Walla Crags NY277214|Walla Crags", "Wastwater Screes NY135025|Wastwater Screes",
      "Whinfell SD588999|Whinfell", "Whitbarrow Scar SD455850|Whitbarrow Scar", "Whitestones SD635984|Whitestones", "Wolf Crags NY362223|Wolf Crags", "Wrynose Pass NY279030|Wrynose Pass"];
  } else if (s1.value == "Dales") {
    var optionArray1 = ["|", "Addingham Moorside SE078471|Addingham Moorside", "Addlebrough SD948880|Addlebrough", "Baildon SE145404|Baildon", "Bishopdale SD950803|Bishopdale", "Brant Side SD778866|Brant Side",
      "Cow Close Fell SD890732|Cow Close Fell", "Cowling and Sutton Pinnacles SD988430|Cowling and Sutton Pinnacles", "Dodd Fell and Grove Head SD829838|Dodd Fell and Grove Head", "Fremington Edge NZ046003|Fremington Edge",
      "Great Whernside SD998751|Great Whernside", "Ilkley Moor SE135464|Ilkley Moor", "Kettlewell SD967711|Kettlewell", "Kilnsey SD971679|Kilnsey", "Nappa Scar SD960925|Nappa Scar", "Semer Water SD909880|Semer Water",
      "Stags Fell SD870927|Stags Fell", "Tailbridge NY804050|Tailbridge", "Wether Fell SD868867|Wether Fell", "Whernside SD725788|Whernside", "Windbank/Hawkswick SD966704|Windbank/Hawkswick"];
  } else if (s1.value == "Derbyshire") {
    var optionArray1 = ["|", "Broadlee Bank SK112860|Broadlee Bank", "Cats Tor SJ994759|Cats Tor", "Cocking Tor SK347607|Cocking Tor", "Curbar SK260748|Curbar", "Dale Head SK096838|Dale Head", "Lords Seat SK110835|Lords Seat", "Mam Tor NW SK127836|Mam Tor NW", "Stanage SK251828|Stanage", "Treak Cliff SK134829|Treak Cliff"];
  } else if (s1.value == "Dover and Folkestone") {
    var optionArray1 = ["|", "Crete Road TR199382|Crete Road", "Herne Bay TR212689|Herne Bay", "Minster TQ958736|Minster", "Warren TR259385|Warren", "Thurnham TQ806582|Thurnham"];
  } else if (s1.value == "East Anglia Coastal") {
    var optionArray1 = ["|", "Corton TM547961|Corton", "Hunstanton TF678423|Hunstanton", "Julie's Field, Mundesley TG318362|Julie's Field, Mundesley", "Mundesley TG300377|Mundesley", "Rocky Bottoms TG190429|Rocky Bottoms", "Weybourne TG112436|Weybourne"];
  } else if (s1.value == "Fat Paramotor") {
    var optionArray1 = ["Fat Paramotor HQ TF055746|Fat Paramotor HQ"];
  } else if (s1.value == "FlyPap") {
    var optionArray1 = ["Lodge Farm SJ693469|Lodge Farm"];
  } else if (s1.value == "Flying Frenzy") {
    var optionArray1 = ["|", "Eggardon SY541949|Eggardon", "Hive Beach Cliff SY493887|Hive Beach Cliff", "New Lane SY522880|New Lane", "Swyre Hill SY530877|Swyre Hill"];
  } else if (s1.value == "Flying Fever") {
    var optionArray1 = ["|", "Bennan Head (South East) NR999211|Bennan Head (South East)", "Bennan Head (South) NR978205|Bennan Head (South)", "Brownhead NR901262|Brownhead", "Catacol NR917498|Catacol", "Corrie Cravie NR923246|Corrie Cravie", "Dereneneach NR937329|Dereneneach", "Drumadoon NR887296|Drumadoon", "Dun Fionn NS045338|Dun Fionn", "Glen Shant NR993388|Glen Shant", "Goat Fell NR992416|Goat Fell", "Laggan Face NR977497|Laggan Face", "Lochranza - Newton Face, Newton Point NR938503|Lochranza - Newton Face, Newton Point", "Lochranza - Torr NR951493|Lochranza - Torr", "Maol Donn NS019406|Maol Donn", "Ross NS003304|Ross", "String NR978351|String", "Suidhe Fhearghas NR989454|Suidhe Fhearghas", "Thunderguy NR899452|Thunderguy", "Windmill NR981349|Windmill",];
  } else if (s1.value == "Foot Flight Paramotors") {
    var optionArray1 = ["Mersea Island TM018145|Mersea Island"];
  } else if (s1.value == "GPPG Flyers") {
    var optionArray1 = ["Little Gransden TL265535|Little Gransden"];
  } else if (s1.value == "Highland") {
    var optionArray1 = ["|", "Am Biorraid NG862279|Am Biorraid", "Bealach Na Ba NG778418|Bealach Na Ba", "Beinn Bhan NG818432|Beinn Bhan", "Beinn Liath Mhor NG971519|Beinn Liath Mhor", "Beinn Na Feusaige NH096543|Beinn Na Feusaige",
     "Beinn Nam Ban NH105911|Beinn Nam Ban", "Ben Wyvis NH450666|Ben Wyvis", "Bruthach Nan Uamh NH190125|Bruthach Nan Uamh", "Cairngorm NJ004047|Cairngorm", "Choire a' Chaorachain NG786421|Choire a' Chaorachain", "Cluanie Inn NH062125|Cluanie Inn",
     "Creag Meagaidh - Na Cnapanan NN476888|Creag Meagaidh - Na Cnapanan", "Creag Meagaidh - Sron a' Choire NN455875|Creag Meagaidh - Sron a' Choire", "Creag Nan Clag NH600288|Creag Nan Clag", "Creag Reidh Rainich NG900254|Creag Reidh Rainich",
     "Fionn Bheinn NH147620|Fionn Bheinn", "Kintail NG944198|Kintail", "Knockfarrel NH503584|Knockfarrel", "Ledmore NC253128|Ledmore", "Liathach NG910574|Liathach", "Little Wyvis NH427642|Little Wyvis", "Macleod's Table North NG221443|Macleod's Table North",
     "Meall a Bhainne NH190756|Meall a Bhainne", "Meall a' Bhuachaille NH990114|Meall a' Bhuachaille", "Meall a' Ghiuthais NG978638|Meall a' Ghiuthais", "Meall Gorm NG777405|Meall Gorm", "Quinag NC223273|Quinag", "Sail Mhor NH042889|Sail Mhor",
     "Spidean Coire Nan Clach (Beinn Eighe) NG967591|Spidean Coire Nan Clach (Beinn Eighe)", "The Quirang NG442675|The Quirang", "Tom Na Gruagaich (Beinn Alligin) NG857598|Tom Na Gruagaich (Beinn Alligin)"];
  } else if (s1.value == "Isle of Wight") {
    var optionArray1 = ["|", "Afton SZ368858|Afton", "Alum Bay SZ302849|Alum Bay", "Arreton SZ446799|Arreton", "Atherfield SZ547871|Atherfield", "Brighstone SZ438837|Brighstone", "Chillerton SZ477834|Chillerton", "Culver SZ632856|Culver", "Downcourt SZ494778|Downcourt", "Limestone SZ440837|Limestone", "Luccombe SZ584802|Luccombe", "Pearl SZ411821|Pearl", "Red Cliffs SZ373849|Red Cliffs", "St Boniface SZ574785|St Boniface", "St Catherine's Down SZ493777|St Catherine's Down", "White Cliffs SZ363856|White Cliffs", "Yaverland SZ619854|Yaverland"];
  } else if (s1.value == "Kernow") {
    var optionArray1 = ["|", "Carbis Bay SW537385|Carbis Bay", "Carn Brea SW685407|Carn Brea", "Carne SW912386|Carne", "Chapel Porth SW700500|Chapel Porth", "Godrevy SW587422|Godrevy", "Hayle Towans SW562394|Hayle Towans", "High Cliff SX127937|High Cliff", "Perran Sands SW762560|Perran Sands", "Sennen SW358263|Sennen", "St. Agnes SW703515|St. Agnes", "Vault Bay SX006407|Vault Bay"];	 
  } else if (s1.value == "Lanarkshire and Lothian") {
    var optionArray1 = ["|", "Abington NS933236|Abington","Aonach Mor NN188762|Aonach Mor","Beinn Ghlas NN622397|Beinn Ghlas","Ben Ledi NN561098|Ben Ledi","Bishop Hill NO177029|Bishop Hill","Broughton Heights NT122414|Broughton Heights",
    "Campsie Fells NS620800|Campsie Fells","Dumyat NS832983|Dumyat","Dungavel NS931305|Dungavel","Fairlie NS221534|Fairlie","Glencoe NN258521|Glencoe","Hillend NT243666|Hillend","Meall nan Tarmachan NN590382|Meall nan Tarmachan",
    "Moorfoots NT348544|Moorfoots","Myreton NS856979|Myreton","Stronend NS613899|Stronend","Thornhill NX920985|Thornhill","Tinto - North NS924345|Tinto - North","Tinto - South NS929343|Tinto - South"];
  } else if (s1.value == "Long Mynd") {
    var optionArray1 = ["|", "Black Knoll SO393887|Black Knoll", "Camlo SSE SO025704|Camlo SSE", "Camlo W SO036697|Camlo W", "Clatter SO004964|Clatter", "Clunbury SO375801|Clunbury", "Corndon SO310967|Corndon", "Lanfawr SO295967|Lanfawr", "Lawley SO493974|Lawley", "Llandinam SO034871|Llandinam", "Llandinam Little London SO044888|Llandinam Little London", "Long Mynd SO404919|Long Mynd", "Shepherds Tump SO154653|Shepherds Tump", "Wrekin SJ631085|Wrekin"];
  } else if (s1.value == "Malvern") {
    var optionArray1 = ["|", "Black Hill SO767407|Black Hill", "Builth Wells SO081523|Builth Wells", "Castlemorton SO762386|Castlemorton", "Leckhampton SO955185|Leckhampton", "Much Marcle SO630329|Much Marcle",
      "Perseverance Hill SO769435|Perseverance Hill", "Pinnacle Hill (Kettle Sings) SO768421|Pinnacle Hill (Kettle Sings)", "Worcestershire Beacon SO769451|Worcestershire Beacon"];
  } else if (s1.value == "Malvern Aerotow") {
  var optionArray1 = ["Croft Farm SO908430|Croft Farm"];
  } else if (s1.value == "Midland") {
    var optionArray1 = ["Sibson SK364010|Sibson"];
  } else if (s1.value == "Mid Wales") {
    var optionArray1 = ["|", "Borth SN595868|Borth", "Cardigan (Poppit) SN138493|Cardigan (Poppit)", "Cemmaes SH865054|Cemmaes", "Constitution Hill SN584828|Constitution Hill", "Craig y Pistyll SN715863|Craig y Pistyll",
      "Disgwylfa Fawr SN734845|Disgwylfa Fawr", "Elan Valley SN909718|Elan Valley", "Fairbourne SH612117|Fairbourne", "Ffarmers SN651479|Ffarmers", "Frenni Fawr SN200350|Frenni Fawr", "Llanddewi Brefi SN653541|Llanddewi Brefi",
      "Moel Fadian SN832951|Moel Fadian", "Pennant SN869963|Pennant", "Pen Dinas SN584802|Pen Dinas"];
  } else if (s1.value == "Norfolk") {
  var optionArray1 = ["Fransham TF902141|Fransham"];
  } else if (s1.value == "North Devon") {
    var optionArray1 = ["|", "Codden Hill SS584296|Codden Hill", "Cornborough SS417288|Cornborough", "Putsborough SS440407|Putsborough", "Trentishoe SS628479|Trentishoe", "Woolacombe SS458426|Woolacombe"];
  } else if (s1.value == "North Devon PPG") {
    var optionArray1 = ["Cranford SS353213|Cranford"];	
  } else if (s1.value == "North Wales") {
    var optionArray1 = ["|", "Great Orme SH765830|Great Orme", "Penycloddiau East SJ127685|Penycloddiau East", "Penycloddiau West SJ125678|Penycloddiau West", "Moel Famau SJ158609|Moel Famau", "Moel Accre SJ169529|Moel Accre",
    "Moel y Faen (Ponderosa) SJ185476|Moel y Faen (Ponderosa)", "Llantysilio SJ152453|Llantysilio", "Llangollen SJ237404|Llangollen", "Gyrn Moelfre SJ184295|Gyrn Moelfre","Thurstaston SJ237833|Thurstaston"];
  } else if (s1.value == "North Yorks") {
    var optionArray1 = ["|", "Busby Moor (Model Ridge) NZ525033|Busby Moor (Model Ridge)", "Captain Cook's Monument NZ598101|Captain Cook's Monument", "Carlton Bank NZ519025|Carlton Bank", "Cringle Moor NZ535034|Cringle Moor",
      "Huntcliffe (Saltburn) NZ671215|Huntcliffe (Saltburn)", "Marske Sand Dunes NZ640228|Marske Sand Dunes", "Summer Hill NZ593114|Summer Hill", "Sutton Bank SE514830|Sutton Bank"];
  } else if (s1.value == "Northern Skies") {
    var optionArray1 = ["Balne Moor SE597196|Balne Moor"];		  
  } else if (s1.value == "Northumbria") {
    var optionArray1 = ["|", "Biddlestone NT948079|Biddlestone", "Clennel Hill NT928076|Clennel Hill", "Crimdon NZ483371|Crimdon", "Cross Fell NY659338|Cross Fell", "East Hill NU038160|East Hill", "Great Tosson NZ011986|Great Tosson", "Hepburn Wood NU078226|Hepburn Wood", "Hogden Law NT961125|Hogden Law", "Hownam Law NT787226|Hownam Law", "Humbeldon Hill NT973284|Humbeldon Hill", "Linbriggs NT900064|Linbriggs", "Long Crag NU065070|Long Crag", "Lordenshaw NZ045986|Lordenshaw", "Lords Seat NT908064|Lords Seat", "Lumsden Law NT719047|Lumsden Law", "Moneylaws NT871347|Moneylaws", "Old Bewick NU069206|Old Bewick", "Seaham NZ423508|Seaham", "Titlington Pike NU086158|Titlington Pike", "West Hill NU026155|West Hill", "Yeavering Bell NT925305|Yeavering Bell"];
  } else if (s1.value == "Peak") {
    var optionArray1 = ["|", "Bunster SK140514|Bunster", "Cats Tor SJ994758|Cats Tor", "Chelmorton SK116708|Chelmorton", "Ecton Hill SK099579|Ecton Hill", "High Wheeldon SK100659|High Wheeldon", "Roaches SK001640|Roaches",
      "Edge Top SK055657|Edge Top", "High Edge SK060690|High Edge", "Shining Tor SJ994737|Shining Tor", "Wetton Hill SK112566|Wetton Hill"];
  } else if (s1.value == "Pennine") {
    var optionArray1 = ["|", "Edenfield SD818179|Edenfield", "Longridge Fell SD657410|Longridge Fell", "Nont Sarahs SE018137|Nont Sarahs", "Parlick SD596451|Parlick", "Pendle SD782403|Pendle", "Pule SE033104|Pule",
      "Winter Hill SD659149|Winter Hill"];
  } else if (s1.value == "Rutland") {
    var optionArray1 = ["North Luffenham SK943045|North Luffenham"];
  } else if (s1.value == "Scotland - Pentlands") {
    var optionArray1 = ["|", "Black Mount NT081460|Black Mount", "Carnethy NT204618|Carnethy"];
  } else if (s1.value == "Scotland - SE Highlands") {
    var optionArray1 = ["|", "Balquhidder NN551220|Balquhidder", "Beinn Ghlas NN622397|Beinn Ghlas", "Ben Ledi NN561098|Ben Ledi", "Burger Van / Beinn Leabhain NN573286|Burger Van / Beinn Leabhain", "Dollar NS954991|Dollar", "Gargunnock NS709929|Gargunnock", "Meall nan Tarmachan NN590382|Meall nan Tarmachan", "Schiehalion NN714547|Schiehalion"];
  } else if (s1.value == "Scotland - W Highlands") {
    var optionArray1 = ["|", "Aberfoyle NN509021|Aberfoyle", "Ben Dorain NN321393|Ben Dorain", "Ben Toaig NN206448|Ben Toaig", "Luss NS350944|Luss"];
  } else if (s1.value == "SE Wales") {
    var optionArray1 = ["|", "Abertysswg SO141059|Abertysswg", "Bedlinog SO100035|Bedlinog", "Bleanavon SO218098|Bleanavon", "Blorenge SO280129|Blorenge", "Cwmbran Mountain Air ST268978|Cwmbran Mountain Air",
      "Cwmbran Ty-canol ST262955|Cwmbran Ty-canol", "Cwmparc SS932951|Cwmparc", "Ebbw Vale SO168065|Ebbw Vale", "Fochriw SO098048|Fochriw", "Garth Hill ST113837|Garth Hill", "Garway SO436250|Garway", "Gelligaer SO116015|Gelligaer",
      "Hay Bluff SO245365|Hay Bluff", "Machen ST225900|Machen", "Merthyr SO078036|Merthyr", "Mynydd Meio ST114883|Mynydd Meio", "Nant-y-Moel SS944938|Nant-y-Moel", "Pandy SO319243|Pandy", "Pontlottyn SO116051|Pontlottyn",
      "Pen Cerrig Calch SO220216|Pen Cerrig Calch", "Rhigos SN926031|Rhigos", "Skirrid Fawr SO330180|Skirrid Fawr", "Sugar Loaf SO271166|Sugar Loaf", "Talybont SO058191|Talybont"];
  } else if (s1.value == "Sky Surfing") {
    var optionArray1 = ["|", "Butser SU712203|Butser", "Chalton SU735150|Chalton", "Harting SU805184|Harting", "Mercury SU675197|Mercury", "Whitewool SU650204|Whitewool"];
  } else if (s1.value == "Snowdonia") {
    var optionArray1 = ["|", "Arenig Fawr SH824368|Arenig Fawr", "Bochlwyd/Cwm Idwal SH655595|Bochlwyd/Cwm Idwal", "Conwy Mountain SH760778|Conwy Mountain", "Elidir Fach (NW/W) SH604613|Elidir Fach (NW/W)",
      "Elidir Fach (W/SW) SH604613|Elidir Fach (W/SW)", "Foel Lus SH733763|Foel Lus", "Foel Lus (NE) SH733763|Foel Lus (NE)", "Glyderau SH663566|Glyderau", "Harlech SH595315|Harlech",
      "Harlech Cliff (Allt y Mor) SH574295|Harlech Cliff (Allt y Mor)", "Ice cream (Viewpoint) SH659542|Ice cream (Viewpoint)", "Moel Berfedd SH652557|Moel Berfedd", "Moel Eilio (Conwy Valley) SH749658|Moel Eilio (Conwy Valley)",
      "Moel Eilio (N/NW) SH566577|Moel Eilio (N/NW)", "Moel Eilio (W/SW) SH566577|Moel Eilio (W/SW)", "Moel Siabod SH705546|Moel Siabod", "Moel Wnion SH649697|Moel Wnion", "Moel y Ci SH591661|Moel y Ci",
      "Nant Gwrtheyrn SH350444|Nant Gwrtheyrn", "Pen yr Helgi Du (E) SH698630|Pen yr Helgi Du (E)", "Pen yr Helgi Du (W) SH698630|Pen yr Helgi Du (W)", "Penmaenbach SH748781|Penmaenbach", "Station 4 SH648548|Station 4",
      "Trefor SH388474|Trefor", "Tremadog SH555408|Tremadog"];
  } else if (s1.value == "South Devon") {
    var optionArray1 = ["|", "Battern Cliffs SX330541|Battern Cliffs", "Beesands SX817400|Beesands", "Belstone SX613906|Belstone", "Chinkwell SX729780|Chinkwell", "Corn Ridge SX553895|Corn Ridge", "Cox Tor SX529764|Cox Tor",
      "Dizzard SX175994|Dizzard", "Freathy SX401518|Freathy", "King Tor SX709815|King Tor", "Maker SX438515|Maker", "Meldon Hill SX695862|Meldon Hill", "Polhawn Fields SX422497|Polhawn Fields", "Sandymouth, Bude SS204098|Sandymouth, Bude",
      "Sourton Tor SX546896|Sourton Tor", "Strete SX835458|Strete", "Struddick Farm SX295545|Struddick Farm", "Trethill Cliffs SX371536|Trethill Cliffs", "Widgery Cross SX539857|Widgery Cross"];
  } else if (s1.value == "Southern") {
    var optionArray1 = ["|", "Beachy Head TV591958|Beachy Head", "Bo Peep TQ500043|Bo Peep", "Devils Dyke TQ258111|Devils Dyke", "Ditchling TQ325132|Ditchling", "Firle TQ478059|Firle", "High and Over TQ510011|High and Over",
      "Mount Caburn TQ444089|Mount Caburn", "Newhaven TQ445001|Newhaven"];
  } else if (s1.value == "SW Wales") {
    var optionArray1 = ["|","Abernant SN693087|Abernant","Bryncaws SN766032|Bryncaws","Cwmafan SS784934|Cwmafan","Fan Gyhirych SN881191|Fan Gyhirych","Ferryside SN363088|Ferryside","Graig Fawr SN619071|Graig Fawr",
      "Heol Senni SN913224|Heol Senni","Lletty Siac SN758028|Lletty Siac","Marros SN200090|Marros","Moel Feity SN856233|Moel Feity","Rhiw Wen SN731193|Rhiw Wen","Rhossili SS420889|Rhossili","Seven Sisters SN832074|Seven Sisters"];
  } else if (s1.value == "Suffolk") {
    var optionArray1 = ["Mendlesham TM135635|Mendlesham"];
  } else if (s1.value == "Team Badgers") {
    var optionArray1 = ["Cowpastures SP334346|Cowpastures"];
  } else if (s1.value == "Thames Valley") {
    var optionArray1 = ["|", "Combe Gibbet SU362623|Combe Gibbet", "Golden Ball SU127638|Golden Ball", "Liddington SU208798|Liddington", "Milk Hill SU101644|Milk Hill", "Milk Hill White Horse SU100637|Milk Hill White Horse",
      "Rybury SU084637|Rybury", "Sugar SU238786|Sugar", "Tan SU085646|Tan", "Uffington White Horse SU302868|Uffington White Horse", "White Hill SU518566|White Hill"];
  } else if (s1.value == "Ufly4fun") {
	var optionArray1 = ["Wingland Airfield TF426261|Wingland Airfield"];
  } else if (s1.value == "Upottery") {
	var optionArray1 = ["Smeatharpe Airfield ST188101|Smeatharpe Airfield"];
  } else if (s1.value == "Wingbeat") {
    var optionArray1 = ["|", "Black Knowe Head NT326218|Black Knowe Head", "Bodesbeck NT169104|Bodesbeck", "Bridgend NT232222|Bridgend", "Eildons NT554328|Eildons", "Fastheugh NT394277|Fastheugh", "Foulshiels NT427302|Foulshiels", "Mountbenger NT313270|Mountbenger", "Newark Hill NT400286|Newark Hill", "Nickies Knowe NT164191|Nickies Knowe", "Sundhope Heights NT340239|Sundhope Heights", "Witchie Knowe NT369258|Witchie Knowe"];
  } else if (s1.value == "Welsh Borders") {
    var optionArray1 = ["Hundred House SO085527|Hundred House"];
  } else if (s1.value == "Wessex") {
    var optionArray1 = ["|", "Ballard Down/Cliff SZ038812|Ballard Down/Cliff", "Barton on Sea SZ241929|Barton on Sea", "Bell Hill ST798085|Bell Hill", "Bulbarrow ST772058|Bulbarrow", "Corton Denham ST633233|Corton Denham",
      "Cowdown Hill SY639999|Cowdown Hill", "Friar Waddon SY641854|Friar Waddon", "Kimmeridge SY926795|Kimmeridge", "Knitson SZ006812|Knitson", "Maiden Castle SY669886|Maiden Castle", "Marleycombe SU023226|Marleycombe",
      "Monk's Down ST939207|Monk's Down", "Okeford Hill ST813095|Okeford Hill", "Portland East SY703718|Portland East", "Portland West SY685728|Portland West", "Ringstead Bay SY758823|Ringstead Bay", "Southbourne SZ130913|Southbourne",
      "St.Aldhelm's Head SY958769|St.Aldhelm's Head", "Swallowcliffe Down ST975256|Swallowcliffe Down", "White Horse SY715844|White Horse", "Whitesheet ST936237|Whitesheet", "Winkelbury ST950214|Winkelbury"];
  } else if (s1.value == "XClent") {
    var optionArray1 = ["Cravens Gorse SP052240|Cravens Gorse"];
  } else if (s1.value == "YX Paragliding") {
  var optionArray1 = ["Long Mountain SJ283094|Long Mountain"];
  }
  for (var option1 in optionArray1) {
    var pair1 = optionArray1[option1].split("|");
    var newOption1 = document.createElement("option");
    newOption1.value = pair1[0];
    newOption1.innerHTML = pair1[1];
    s2.options.add(newOption1);
  }
}

function SetDate() {
  var nowdate = new Date(new Date().getTime());
// Here's the suggested day for CANP. It is cut off or weekend days when LFC closes on Friday afternoon.
  if (nowdate.getDay() == 6) { // is it Sat?
    var nextdate = new Date(new Date().getTime() + 2 * 24 * 3600 * 1000);
  } else if ((nowdate.getDay() == 5) && (nowdate.getHours() > 14)) { // is it Friay afternoon?
    var nextdate = new Date(new Date().getTime() + 3 * 24 * 3600 * 1000);
  } else {
    var nextdate = new Date(new Date().getTime() + 24 * 3600 * 1000);
  }
  var nextday = nextdate.getDate();
  var month = nextdate.getMonth() + 1;
  var year = nextdate.getFullYear();
  if (month < 10) month = "0" + month;
  if (nextday < 10) nextday = "0" + nextday;
  var tomorrow = year + "-" + month + "-" + nextday;
  document.getElementById('notifDate').value = tomorrow;
  // this needs to appear on Monday if today is a weekend day or after LFC closure on Friday.

  if (nowdate.getDay() == 6) { // is it Sat?
    var thisdate = new Date(new Date().getTime() + 2 * 24 * 3600 * 1000);
  } else if (nowdate.getDay() == 0) { // is it Sun?
    var thisdate = new Date(new Date().getTime() + 24 * 3600 * 1000);
  } else if ((nowdate.getDay() == 5) && (nowdate.getHours() > 14)) { // is it Friday afternoon or eve?
    var thisdate = new Date(new Date().getTime() + 3 * 24 * 3600 * 1000);
  } else {
    var thisdate = new Date(new Date().getTime());
  }
  var thisday = thisdate.getDate(); // return the numeric day of the month
  var thismonth = thisdate.getMonth() + 1; // we add 1 because month number starts at zero for Jan
  var thisyear = thisdate.getFullYear(); // i.e. 2020
  if (thismonth < 10) thismonth = "0" + thismonth; // adds a zero to month for single digit months
  if (thisday < 10) thisday = "0" + thisday; // adds a zero to day number for single digit days
  var today = thisyear + "-" + thismonth + "-" + thisday; // formats the date string 2020-02-05
  document.getElementById('notifDate').min = today;
  // this needs to appear on Monday if today is a weekend day.

  var lmtdate = new Date(new Date().getTime() + 6 * 24 * 3600 * 1000);
  var lmtday = lmtdate.getDate();
  var lmtmonth = lmtdate.getMonth() + 1;
  var lmtyear = lmtdate.getFullYear();
  if (lmtmonth < 10) lmtmonth = "0" + lmtmonth;
  if (lmtday < 10) lmtday = "0" + lmtday;
  var lmtday = lmtyear + "-" + lmtmonth + "-" + lmtday;
  document.getElementById('notifDate').max = lmtday;
  // always set out at six days ahead.
  getRecent();
  // function for remote load CANPs left in html because they're env specific.
}
