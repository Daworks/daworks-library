<?php
	
	/**
	 * Provide a admin area view for the plugin
	 *
	 * This file is used to markup the admin-facing aspects of the plugin.
	 *
	 * @link       https://daworks.io
	 * @since      1.0.0
	 *
	 * @package    Church_library
	 * @subpackage Church_library/admin/partials
	 */
	global $wpdb;
	
	require plugin_dir_path( __DIR__ ) . '../PHP-Pagination/Pagination.class.php';
	
	$table = $wpdb -> prefix . 'book_info';
	$limit = 20;
	$current_page = NULL !== $_REQUEST[ 'current' ] ? $_REQUEST[ 'current' ] : 1;
	$start_point = $current_page * $limit - $limit;
	$req = $_REQUEST;
	
	if ( null !== $req['search_type'] ) {
		$i = 0;
		$len = count($req['search_type'] );
		$searchArr = [];
		foreach( $req['search_type']  as $type ) {
			if ( $i === 0 ) {
				$query = "select * from {$table} where {$type} like concat('%','%s','%') ";
				$total_query = "select count(*) from {$table} where {$type} like concat('%','%s','%') ";
			}
			else {
				$query .= "OR {$type} like concat('%','%s','%') ";
				$total_query .= "OR {$type} like concat('%','%s','%') ";
			}
			array_push($searchArr, $req['keyword']);
			++$i;
		}
		$query .= " ORDER BY TITLE ASC LIMIT {$start_point}, {$limit}";
		
		$results = $wpdb->get_results($wpdb->prepare($query, $searchArr));
		$total = $wpdb->get_var($wpdb->prepare($total_query, $searchArr));
	}
	else {
		$query = "SELECT * FROM {$table} ";
		$query .= " ORDER BY TITLE ASC LIMIT {$start_point}, {$limit}";
		$results = $wpdb -> get_results( $query );
		$total = $wpdb->get_var("select count(*) total from {$table}");
	}
	$pagination = new Pagination();
	$pagination -> setKey( 'current' );
	$pagination -> setCrumbs( 10 );
	$pagination -> setRPP( $limit );
	$pagination -> setCurrent( $current_page );
	$pagination -> setTotal( $total );
	
	$wpdb->flush();
	
	$search_type = (null !== $_REQUEST['search_type']) ? $_REQUEST['search_type'] : '';
	
	function isInKey($array = [], $needle = null )
	{
		if ( !empty($array) && null !== $needle ) {
			if ( in_array( $needle, $array ) ) {
				return 'checked';
			}
		}
	}

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="row">
	<div class="col-sm-12">
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="row">
					<div class="col-sm-2">
						<span class="label label-success">TOTAL</span> <b><?php echo number_format( $total ); ?></b> 권
					</div>
					<div class="col-sm-10">
						<form class="form-inline" id="search">
							<input type="hidden" name="page" value="church-library">
							<div class="form-group searchbox">
								<label for="search" class="sr-only">검색</label>
								<label><input type="checkbox" name="search_type[]" value="title" <?php isInKey( $_REQUEST[ 'search_type' ], 'title' ) ?> checked> 제목</label>
								<label><input type="checkbox" name="search_type[]" value="writer" <?php isInKey( $_REQUEST[ 'search_type' ], 'writer' ) ?>> 저자</label>
								<label><input type="checkbox" name="search_type[]" value="publisher" <?php isInKey( $_REQUEST[ 'search_type' ], 'publisher' ) ?>> 출판사</label>
								<label><input type="checkbox" name="search_type[]" value="published_year" <?php isInKey($_REQUEST['search_type'], 'published_year') ?>> 발행년도</label>
								<label><input type="checkbox" name="search_type[]" value="classified_code" <?php isInKey($_REQUEST['search_type'], 'classified_code') ?>> 분류기호</label>
								<label><input type="checkbox" name="search_type[]" value="book_code" <?php isInKey($_REQUEST['search_type'], 'book_code') ?>> 서적기호</label>
								<label><input type="checkbox" name="search_type[]" value="ISBN" <?php isInKey($_REQUEST['search_type'], 'ISBN') ?>> ISBN</label>
							</div>
							<div class="form-group">
								<div class="input-group input-group-sm">
									<input type="text" name="keyword" id="keyword" class="form-control input-sm" placeholder="검색어..." value="<?php echo (null != $_REQUEST['keyword']) ? $_REQUEST['keyword'] : '' ?>">
									<span class="input-group-btn">
										<button class="btn btn-sm btn-info">검색</button>
									</span>
								</div>
								<button type="button" class="btn btn-sm btn-default" onclick="formreset()">다시검색</button>
							</div>
						</form>
					</div>
				</div>

			</div>
		</div>

		<table class="table table-hover daworks_church_library_table">
			<thead>
			<tr>
				<th>제목</th>
				<th>저자</th>
				<th class="text-center">보유</th>
				<th>출판사</th>
				<th>발행년도</th>
				<th>분류기호</th>
				<th>약호</th>
				<th>ISBN</th>
			</tr>
			</thead>
			<tbody>
				 <?php
					 if ( count($results) === 0 ) {
					   printf('<tr><td colspan="8" class="text-center">검색된 결과가 없습니다.</td></tr>');
					 }
					 
					 foreach ( $results as $row ) {
						 printf( '
										<tr id="book_%s">
											<td>%s</td>
											<td>%s</td>
											<td class="text-center">%d</td>
											<td>%s</td>
											<td>%s</td>
											<td>%s</td>
											<td>%s</td>
											<td>%s</td>
										</tr>', $row -> id, $row -> title, $row -> writer, $row -> count, $row -> publisher, $row -> published_year, $row -> classified_code, $row -> book_code, $row -> isbn );
					 }
				 ?>
			</tbody>
		</table>
		<div class="text-center" style="margin-top:30px;">
		 <?php echo $pagination -> parse() ?>
		</div>
	</div>
</div>
