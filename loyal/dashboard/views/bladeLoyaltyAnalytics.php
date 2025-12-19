<?php 
// Get date range filters
$dateFilter = $_GET['period'] ?? 'month';
$startDate = '';
$endDate = date('Y-m-d');

switch ($dateFilter) {
	case 'today':
		$startDate = date('Y-m-d');
		break;
	case 'week':
		$startDate = date('Y-m-d', strtotime('-7 days'));
		break;
	case 'month':
		$startDate = date('Y-m-d', strtotime('-30 days'));
		break;
	case 'year':
		$startDate = date('Y-m-d', strtotime('-365 days'));
		break;
	default:
		$startDate = date('Y-m-d', strtotime('-30 days'));
}

// Get platform statistics
$stats = array();

// Total Users
$usersQuery = "SELECT COUNT(*) as count FROM users WHERE status = '0'";
$usersResult = queryDB($usersQuery);
$stats['totalUsers'] = ($usersResult && is_array($usersResult)) ? intval($usersResult[0]['count']) : 0;

// New Users (in period)
$newUsersQuery = "SELECT COUNT(*) as count FROM users WHERE status = '0' AND date >= '$startDate'";
$newUsersResult = queryDB($newUsersQuery);
$stats['newUsers'] = ($newUsersResult && is_array($newUsersResult)) ? intval($newUsersResult[0]['count']) : 0;

// Total Stores
$storesQuery = "SELECT COUNT(*) as count FROM stores WHERE status = '0' AND isApproved = '1'";
$storesResult = queryDB($storesQuery);
$stats['totalStores'] = ($storesResult && is_array($storesResult)) ? intval($storesResult[0]['count']) : 0;

// Active Cards
$cardsQuery = "SELECT COUNT(*) as count FROM customer_cards WHERE status = '0'";
$cardsResult = queryDB($cardsQuery);
$stats['totalCards'] = ($cardsResult && is_array($cardsResult)) ? intval($cardsResult[0]['count']) : 0;

// Total Points Issued
$pointsQuery = "SELECT SUM(points) as total FROM points_transactions WHERE transactionType = 'earned' AND status = '0'";
$pointsResult = queryDB($pointsQuery);
$stats['totalPointsIssued'] = ($pointsResult && is_array($pointsResult)) ? intval($pointsResult[0]['total'] ?? 0) : 0;

// Total Points Redeemed
$redeemedQuery = "SELECT SUM(pointsCost) as total FROM redemptions WHERE redemptionStatus = 'completed'";
$redeemedResult = queryDB($redeemedQuery);
$stats['totalPointsRedeemed'] = ($redeemedResult && is_array($redeemedResult)) ? intval($redeemedResult[0]['total'] ?? 0) : 0;

// Active Points Balance
$stats['activePointsBalance'] = $stats['totalPointsIssued'] - $stats['totalPointsRedeemed'];

// Total Transactions (in period)
$transactionsQuery = "SELECT COUNT(*) as count FROM points_transactions WHERE status = '0' AND date >= '$startDate'";
$transactionsResult = queryDB($transactionsQuery);
$stats['periodTransactions'] = ($transactionsResult && is_array($transactionsResult)) ? intval($transactionsResult[0]['count']) : 0;
echo "<!--"; print_r($stats); echo "-->";die();
// Total Transaction Value (in period)
$valueQuery = "SELECT SUM(amount) as total FROM points_transactions WHERE transactionType = 'earned' AND status = '0' AND date >= '$startDate'";
$valueResult = queryDB($valueQuery);
$stats['periodTransactionValue'] = ($valueResult && is_array($valueResult)) ? floatval($valueResult[0]['total'] ?? 0) : 0;

// Total Redemptions (in period)
$redemptionsQuery = "SELECT COUNT(*) as count FROM redemptions WHERE redemptionStatus = 'completed' AND date >= '$startDate'";
$redemptionsResult = queryDB($redemptionsQuery);
$stats['periodRedemptions'] = ($redemptionsResult && is_array($redemptionsResult)) ? intval($redemptionsResult[0]['count']) : 0;

// Get top stores by members
$topStoresQuery = "SELECT s.id, s.enStoreName, s.arStoreName, COUNT(DISTINCT cc.customerId) as memberCount
				   FROM stores s
				   JOIN loyalty_programs lp ON s.id = lp.storeId
				   JOIN customer_cards cc ON lp.id = cc.programId
				   WHERE s.status = '0' AND s.isApproved = '1' AND cc.status = '0'
				   GROUP BY s.id
				   ORDER BY memberCount DESC
				   LIMIT 10";
$topStores = queryDB($topStoresQuery);
if (!$topStores || !is_array($topStores)) $topStores = array();

// Get recent transactions for chart
$chartQuery = "SELECT DATE(date) as day, COUNT(*) as count, SUM(amount) as value
			   FROM points_transactions
			   WHERE type = 'earned' AND status = '0' AND date >= '$startDate'
			   GROUP BY DATE(date)
			   ORDER BY day ASC";
$chartData = queryDB($chartQuery);
if (!$chartData || !is_array($chartData)) $chartData = array();
?>

<div class="row">
<!-- Period Filter -->
<div class="col-sm-12">
	<div class="panel panel-default card-view">
		<div class="panel-heading">
			<div class="pull-left">
				<h6 class="panel-title txt-dark"><?php echo direction("Loyalty Platform Analytics","تحليلات منصة الولاء") ?></h6>
			</div>
			<div class="pull-right">
				<select class="form-control" onchange="window.location.href='?v=LoyaltyAnalytics&period='+this.value">
					<option value="today" <?php echo $dateFilter == 'today' ? 'selected' : '' ?>><?php echo direction("Today","اليوم") ?></option>
					<option value="week" <?php echo $dateFilter == 'week' ? 'selected' : '' ?>><?php echo direction("Last 7 Days","آخر 7 أيام") ?></option>
					<option value="month" <?php echo $dateFilter == 'month' ? 'selected' : '' ?>><?php echo direction("Last 30 Days","آخر 30 يوم") ?></option>
					<option value="year" <?php echo $dateFilter == 'year' ? 'selected' : '' ?>><?php echo direction("Last Year","العام الماضي") ?></option>
				</select>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
</div>

<!-- Statistics Cards -->
<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
	<div class="panel panel-default card-view pa-0">
		<div class="panel-wrapper collapse in">
			<div class="panel-body pa-0">
				<div class="sm-data-box bg-primary">
					<div class="container-fluid">
						<div class="row">
							<div class="col-xs-6 text-center pl-0 pr-0 data-wrap-left">
								<span class="txt-light block counter"><span class="counter-anim"><?php echo number_format($stats['totalUsers']) ?></span></span>
								<span class="weight-500 uppercase-font txt-light block"><?php echo direction("Total Users","إجمالي المستخدمين") ?></span>
								<span class="txt-light block"><small>(+<?php echo $stats['newUsers'] ?> <?php echo direction("new","جديد") ?>)</small></span>
							</div>
							<div class="col-xs-6 text-center  pl-0 pr-0 data-wrap-right">
								<i class="icon-users data-right-rep-icon txt-light"></i>
							</div>
						</div>	
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
	<div class="panel panel-default card-view pa-0">
		<div class="panel-wrapper collapse in">
			<div class="panel-body pa-0">
				<div class="sm-data-box bg-success">
					<div class="container-fluid">
						<div class="row">
							<div class="col-xs-6 text-center pl-0 pr-0 data-wrap-left">
								<span class="txt-light block counter"><span class="counter-anim"><?php echo number_format($stats['totalStores']) ?></span></span>
								<span class="weight-500 uppercase-font txt-light block"><?php echo direction("Active Stores","المتاجر النشطة") ?></span>
							</div>
							<div class="col-xs-6 text-center  pl-0 pr-0 data-wrap-right">
								<i class="icon-home data-right-rep-icon txt-light"></i>
							</div>
						</div>	
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
	<div class="panel panel-default card-view pa-0">
		<div class="panel-wrapper collapse in">
			<div class="panel-body pa-0">
				<div class="sm-data-box bg-warning">
					<div class="container-fluid">
						<div class="row">
							<div class="col-xs-6 text-center pl-0 pr-0 data-wrap-left">
								<span class="txt-light block counter"><span class="counter-anim"><?php echo number_format($stats['totalCards']) ?></span></span>
								<span class="weight-500 uppercase-font txt-light block"><?php echo direction("Active Cards","البطاقات النشطة") ?></span>
							</div>
							<div class="col-xs-6 text-center  pl-0 pr-0 data-wrap-right">
								<i class="icon-credit-card data-right-rep-icon txt-light"></i>
							</div>
						</div>	
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
	<div class="panel panel-default card-view pa-0">
		<div class="panel-wrapper collapse in">
			<div class="panel-body pa-0">
				<div class="sm-data-box bg-danger">
					<div class="container-fluid">
						<div class="row">
							<div class="col-xs-6 text-center pl-0 pr-0 data-wrap-left">
								<span class="txt-light block counter"><span class="counter-anim"><?php echo number_format($stats['activePointsBalance']) ?></span></span>
								<span class="weight-500 uppercase-font txt-light block"><?php echo direction("Points Balance","رصيد النقاط") ?></span>
							</div>
							<div class="col-xs-6 text-center  pl-0 pr-0 data-wrap-right">
								<i class="icon-star data-right-rep-icon txt-light"></i>
							</div>
						</div>	
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Period Statistics -->
<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
	<div class="panel panel-default card-view">
		<div class="panel-heading">
			<div class="pull-left">
				<h6 class="panel-title txt-dark"><?php echo direction("Transactions","المعاملات") ?></h6>
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="panel-wrapper collapse in">
			<div class="panel-body">
				<div class="text-center">
					<h2 class="text-primary"><?php echo number_format($stats['periodTransactions']) ?></h2>
					<p><?php echo direction("Total Transactions","إجمالي المعاملات") ?></p>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
	<div class="panel panel-default card-view">
		<div class="panel-heading">
			<div class="pull-left">
				<h6 class="panel-title txt-dark"><?php echo direction("Transaction Value","قيمة المعاملات") ?></h6>
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="panel-wrapper collapse in">
			<div class="panel-body">
				<div class="text-center">
					<h2 class="text-success"><?php echo number_format($stats['periodTransactionValue'], 2) ?> SAR</h2>
					<p><?php echo direction("Total Value","القيمة الإجمالية") ?></p>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
	<div class="panel panel-default card-view">
		<div class="panel-heading">
			<div class="pull-left">
				<h6 class="panel-title txt-dark"><?php echo direction("Redemptions","الاستردادات") ?></h6>
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="panel-wrapper collapse in">
			<div class="panel-body">
				<div class="text-center">
					<h2 class="text-warning"><?php echo number_format($stats['periodRedemptions']) ?></h2>
					<p><?php echo direction("Rewards Redeemed","المكافآت المستردة") ?></p>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Transactions Chart -->
<div class="col-sm-12">
	<div class="panel panel-default card-view">
		<div class="panel-heading">
			<div class="pull-left">
				<h6 class="panel-title txt-dark"><?php echo direction("Transactions Trend","اتجاه المعاملات") ?></h6>
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="panel-wrapper collapse in">
			<div class="panel-body">
				<canvas id="transactionsChart" height="80"></canvas>
			</div>
		</div>
	</div>
</div>

<!-- Top Stores -->
<div class="col-sm-12">
	<div class="panel panel-default card-view">
		<div class="panel-heading">
			<div class="pull-left">
				<h6 class="panel-title txt-dark"><?php echo direction("Top Stores by Members","أفضل المتاجر حسب الأعضاء") ?></h6>
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="panel-wrapper collapse in">
			<div class="panel-body">
				<div class="table-wrap">
					<div class="table-responsive">
						<table class="table table-hover mb-0">
							<thead>
								<tr>
									<th>#</th>
									<th><?php echo direction("Store Name","اسم المتجر") ?></th>
									<th><?php echo direction("Members","الأعضاء") ?></th>
									<th><?php echo direction("Growth","النمو") ?></th>
								</tr>
							</thead>
							<tbody>
								<?php 
								if (count($topStores) > 0):
									foreach ($topStores as $index => $store): 
								?>
								<tr>
									<td><?php echo $index + 1 ?></td>
									<td>
										<strong><?php echo direction(urldecode($store['enStoreName']), urldecode($store['arStoreName'])) ?></strong>
									</td>
									<td><span class="badge badge-primary"><?php echo number_format($store['memberCount']) ?></span></td>
									<td>
										<div class="progress" style="height:20px">
											<div class="progress-bar bg-success" style="width:<?php echo min(100, ($store['memberCount'] / $topStores[0]['memberCount']) * 100) ?>%"></div>
										</div>
									</td>
								</tr>
								<?php 
									endforeach;
								else:
								?>
								<tr>
									<td colspan="4" class="text-center"><?php echo direction("No data available","لا توجد بيانات متاحة") ?></td>
								</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
<script>
// Transactions Chart
var ctx = document.getElementById('transactionsChart').getContext('2d');
var chartData = <?php echo json_encode($chartData); ?>;

var labels = chartData.map(function(item) {
	return item.day;
});

var counts = chartData.map(function(item) {
	return parseInt(item.count);
});

var values = chartData.map(function(item) {
	return parseFloat(item.value || 0);
});

var transactionsChart = new Chart(ctx, {
	type: 'line',
	data: {
		labels: labels,
		datasets: [{
			label: '<?php echo direction("Transaction Count","عدد المعاملات") ?>',
			data: counts,
			borderColor: 'rgb(75, 192, 192)',
			backgroundColor: 'rgba(75, 192, 192, 0.2)',
			tension: 0.1,
			yAxisID: 'y'
		}, {
			label: '<?php echo direction("Transaction Value (SAR)","قيمة المعاملات (ريال)") ?>',
			data: values,
			borderColor: 'rgb(255, 99, 132)',
			backgroundColor: 'rgba(255, 99, 132, 0.2)',
			tension: 0.1,
			yAxisID: 'y1'
		}]
	},
	options: {
		responsive: true,
		interaction: {
			mode: 'index',
			intersect: false,
		},
		scales: {
			y: {
				type: 'linear',
				display: true,
				position: 'left',
				title: {
					display: true,
					text: '<?php echo direction("Count","العدد") ?>'
				}
			},
			y1: {
				type: 'linear',
				display: true,
				position: 'right',
				title: {
					display: true,
					text: '<?php echo direction("Value (SAR)","القيمة (ريال)") ?>'
				},
				grid: {
					drawOnChartArea: false,
				}
			}
		}
	}
});
</script>
