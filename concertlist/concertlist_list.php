<!DOCTYPE html>
<html>
<head>
	<?php include "../includes/concert_list_head.html";
	include "../includes/regular_page_head.php";?>
	<title>All Recommendation List</title>
	    <style type="text/css">
    #tfheader{
        float:center;
        background-color:#2e343e;
    }
    #tfnewsearch{
        float:center;
        padding:20px;
    }
    .tftextinput{
        margin: 0;
        padding: 5px 10px;
        font-family: Arial, Helvetica, sans-serif;
        font-size:14px;
        border:1px solid #0076a3; border-right:0px;
        border-top-left-radius: 5px 5px;
        border-bottom-left-radius: 5px 5px;
    }
    .tfbutton {
        margin: 0;
        padding: 5px 15px;
        font-family: Arial, Helvetica, sans-serif;
        font-size:14px;
        outline: none;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        color: #ffffff;
        border: solid 1px #0076a3; border-right:0px;
        background: #0095cd;
        background: -webkit-gradient(linear, left top, left bottom, from(#00adee), to(#0078a5));
        background: -moz-linear-gradient(top,  #00adee,  #0078a5);
        border-top-right-radius: 5px 5px;
        border-bottom-right-radius: 5px 5px;
    }
    .tfbutton:hover {
        text-decoration: none;
        background: #007ead;
        background: -webkit-gradient(linear, left top, left bottom, from(#0095cc), to(#00678e));
        background: -moz-linear-gradient(top,  #0095cc,  #00678e);
    }
    /* Fixes submit button height problem in Firefox */
    .tfbutton::-moz-focus-inner {
      border: 0;
    }
    .tfclear{
        clear:both;
    }
</style>
</head>

<?php
//get subtype conncertlist
$username = $_SESSION['username'];
$concertlist_array = array();
$listname="";


if (isset($_POST['button'])) {
    if($_POST['button'] == 'Keyword'){
        if(isset($_POST['keyword'])){
            $keyword = $_POST['keyword'];
            if($search_concertlist = $mysqli->query("call get_keyword_recommandconcert('$keyword')") or die($mysqli->error)){
                while($row =$search_concertlist->fetch_object()){
				$concertlist_result = array();
				$concertlist_result['listname'] = $row->listname;
				$concertlist_result['ldiscrip'] = $row->ldescription;
				$concertlist_result['createby'] = $row->username;
				$concertlist_result['createtime'] = $row->lcreatetime;
				$concertlist_array[] = $concertlist_result;
                }
                $search_concertlist->close();
                $mysqli->next_result();
            }
        }else{
            echo "No keyword input";
        }
    }
}
else if(isset($_GET['type']) && isset($_GET['subtype'])){
	$subtype = $_GET['subtype'];
	if($subtypeList = $mysqli->query("call get_subtype_list('$subtype')") or die($mysqli->error)){
		while($row = $subtypeList->fetch_object()){
			$concertlist_result = array();
			$concertlist_result['listname'] = $row->listname;
			$concertlist_result['ldiscrip'] = $row->ldescription;
			$concertlist_result['createby'] = $row->username;
			$concertlist_result['createtime'] = $row->lcreatetime;
			$concertlist_array[] = $concertlist_result;
			
		}
		$subtypeList->close();
		$mysqli->next_result();
	}
//get type concertlist
}else if(isset($_GET['type'])){
	$type = $_GET['type'];
	if($typeList = $mysqli->query("call get_type_list('$type')")){
		while($row = $typeList->fetch_object()){
			$concertlist_result = array();
			$concertlist_result['listname'] = $row->listname;
			$concertlist_result['ldiscrip'] = $row->ldescription;
			$concertlist_result['createby'] = $row->username;
			$concertlist_result['createtime'] = $row->lcreatetime;
			$concertlist_array[] = $concertlist_result;
		}
		$typeList->close();
		$mysqli->next_result();
	}
//get all concertlist
}else{
	if($allList = $mysqli->query("call get_all_list()")){
		while($row = $allList->fetch_object()){
			$concertlist_result = array();
			$concertlist_result['listname'] = $row->listname;
			$concertlist_result['ldiscrip'] = $row->ldescription;
			$concertlist_result['createby'] = $row->username;
			$concertlist_result['createtime'] = $row->lcreatetime;
			$concertlist_array[] = $concertlist_result;
		}
		$allList->close();
		$mysqli->next_result();
//get
	}
}

$you_may_like = array();
if($systemRecommend = $mysqli->query("call recommend_list_most_follower_similar_taste('$username')") or die($mysqli->error)){
	while($row = $systemRecommend->fetch_object()){

		$concertlist_result = array();
		$concertlist_result['listname'] = $row->listname;
		$concertlist_result['ldiscrip'] = $row->ldescription;
		$concertlist_result['createby'] = $row->username;
		$concertlist_result['createtime'] = $row->lcreatetime;
		$you_may_like[] = $concertlist_result;
	}
	$systemRecommend->close();
	$mysqli->next_result();
}
?>

</div>
<!-- This will show the all the concertrecommend list separate by page
and the system recommend recommedlist with similar taste
then if user click one taste it will show this kind of taste recommendlist  -->
<body id="page2">
		<div class="extra">
        <section id="tfheader">
            <form id="tfnewsearch" method="POST" action="concertlist_list.php">
                <tr><td></td><td>
            <input type = "text" class="tftextinput" name="keyword" size="21" maxlength="80" value='' placeholder='input keyword' >
                <input type="submit" name='button' value="Keyword" class="tfbutton">
        </form>
        </section>
<!--==============================content================================-->
			<section id="content">
			<div id="sidebar">
				<?php
					if($alltype = $mysqli->prepare("select typename from Type")){
						$alltype->execute();
						$alltype->bind_result($typename);
						$getAllType = array();
						while($alltype->fetch()){
							array_push($getAllType,$typename);
						}
						$alltype->close();
						foreach ($getAllType as $key) {
							echo "<ul><a href='/LiveConcert/concertlist/concertlist_list.php?type=$key'>$key</a></ul>";
							if($allsubtype = $mysqli->query("call onetypeallsubtype('$key')")){
								// echo "<ul>";
								while($row = $allsubtype->fetch_object()){
									$subtypename = $row->subtypename;
									echo "<li><a href='/LiveConcert/concertlist/concertlist_list.php?type=$key&subtype=$subtypename'>$subtypename</li>";
								}
								$allsubtype->close();
								$mysqli->next_result();
							}
						}
					}

				?>
			</div>
			<div class="main">

					<div class="content-padding-2">
						<div class="zerogrid">
							<div class="row">
								<div class="col-full">
									<div class="padding-grid-1">
										<h3 class="letter">USER's&nbsp;&nbsp;&nbsp;<strong>Recommendation List </strong></h3>
									</div>

									<?php 
									foreach ($concertlist_array as $key ) {
										$listname = $key['listname'];
										echo "<div class='wrapper p3'>
												<article class='col-1-3'>
													<div class='padding-grid-2'>
														<div class='wrapper'>
															<figure class='style-img-2 fleft'><a href='/LiveConcert/concertlist/concertlist_page.php?listname=$listname'>
															<img height='250px' width='250px' src='/LiveConcert/assets/images/$listname.jpg' ></a></figure>
														</div>
													</div>
												</article>
												<article class='col-2-3'>
													<div class='padding-grid-2'>
														<a href='/LiveConcert/concertlist/concertlist_page.php?listname=$listname'>
														<h4 class='margin-none indent-top1'><strong>$listname&nbsp;&nbsp;&nbsp;</strong> &nbsp;&nbsp;&nbsp;by&nbsp;".$key['createby']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;at&nbsp;".$key['createtime']."</h4></a>
														<p class='prev-indent-bot'>".$key['ldiscrip']."</p>
														<div class='wrapper'>
															<ul class='list-1 fleft'>";
															echo "<ul><h3>Concert</h3></ul>";
										
										if($getConcert = $mysqli->query("call get_recommend_list_concert('$listname')") or die($mysqli->error)){
											if($getConcert->num_rows > 0){
												while($row = $getConcert->fetch_object()){
													$concert = $row->cname;
													echo "<li><a href='/LiveConcert/concert/concert_page.php?cname=$concert'>$concert</a></li>";
													//remove concert button
												}
											}
											$getConcert->close();
											$mysqli->next_result();

										}
										echo "</ul></div>
											</div>
										</article>
									</div>";
									}
								?>
								</div>
								<div class="col-full">
									<div class="padding-grid-2">
										<h3 class="letter">YOU&nbsp;&nbsp;&nbsp;<strong>May Like </strong></h3>
									</div>

								<?php 
									foreach ($you_may_like as $key ) {
										$listname = $key['listname'];
										echo "<div class='wrapper p3'>
												<article class='col-1-3'>
													<div class='padding-grid-2'>
														<div class='wrapper'>
															<figure class='style-img-2 fleft'><a href='/LiveConcert/concertlist/concertlist_page.php?listname=$listname'>
															<img height='250px' width='250px' src='/LiveConcert/assets/images/$listname.jpg' ></a></figure>
														</div>
													</div>
												</article>
												<article class='col-2-3'>
													<div class='padding-grid-2'>
														<a href='/LiveConcert/concertlist/concertlist_page.php?listname=$listname'>
														<h4 class='margin-none indent-top1'><strong>$listname&nbsp;&nbsp;&nbsp;</strong> &nbsp;&nbsp;&nbsp;by&nbsp;".$key['createby']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;at&nbsp;".$key['createtime']."</h4></a>
														<p class='prev-indent-bot'>".$key['ldiscrip']."</p>
														<div class='wrapper'>
															<ul class='list-1 fleft'>";
															echo "<ul><h3>Concert</h3></ul>";
										
										if($getConcert = $mysqli->query("call get_recommend_list_concert('$listname')") or die($mysqli->error)){
											if($getConcert->num_rows > 0){
												while($row = $getConcert->fetch_object()){
													$concert = $row->cname;
													echo "<li><a href='/LiveConcert/concert/concert_page.php?cname=$concert'>$concert</a></li>";
													//remove concert button
												}
											}
											$getConcert->close();
											$mysqli->next_result();

										}
										echo "</ul></div>
											</div>
										</article>
									</div>";
								}
								$mysqli->close();

								?>

								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="block"></div>
			</section>
		</div>

		<script type="text/javascript"> Cufon.now(); </script>
	</body>
</html>
