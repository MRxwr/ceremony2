<style>
.centered {
    position: absolute;
    top: 13px;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    background-color: #135fad;
}
.centered1 {
    position: absolute;
    top: 13px;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    background-color: #2853a8;
}
.centered2 {
    position: absolute;
    top: 131px;
    left: 52%;
    min-height: 25px;
    transform: translate(-50%, -50%);
    color: black;
    background-color: #ffffff;
}
@media only screen and (max-width: 600px) {
	.centered2 {
		position: absolute;
		top: 118px;
		left: 52%;
		min-height: 25px;
		transform: translate(0%, 0%);
		color: black;
		background-color: #ffffff;
	}
}
.tabHead{
	padding: 15px;
    color: black;
    font-weight: 700;
    font-size: 16px;
	width: 100%;
    background-color: #f2f2f2;
}
.card-view.panel .panel-body {
    padding: 0px 0 0px;
}
.card-view{
	padding: 0px 15px 0;
}
.statsHeading{
	background-color: #f2f2f2;
	font-size:22px;
	font-weight:700;
	border-radius: 10px;
    margin-bottom: 10px;
}
</style>

<div class="row" style="padding:16px">
    <div class="col-12">
    Welcome To <?php echo $settingsTitle ?> CP
    </div>
</div>