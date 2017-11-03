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
<h3 class="page-title">도서관리</h3>

<?php if ( filter_input( INPUT_GET, 'msg') ) : ?>
	<div class="row">
		<div class="col-sm-11">
			<div class="alert alert-info" id="info-alert">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					 <?php echo sanitize_text_field ( $_REQUEST['msg'])  ?>
			</div>
		</div>
	</div>
	<script>
	  (function($){
		   setTimeout(function(){
			   $('#info-alert').fadeOut(300);
		   }, 3000);
	  })(jQuery)
	</script>
<?php endif ?>

<div class="row">
	<div class="col-sm-11">
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="row">
					<div class="col-sm-12">
						<form action="<?php echo admin_url( 'admin.php' ); ?>" class="form-inline">
							<input type="hidden" name="page" value="church-library">
							<div class="form-group">
								<span class="label label-success">TOTAL</span> <b><?php echo number_format( $total ); ?></b> 권
							</div>
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
									<input type="text" name="keyword" class="form-control input-sm" placeholder="검색어..." value="<?php echo (null != $_REQUEST['keyword']) ? $_REQUEST['keyword'] : '' ?>">
									<span class="input-group-btn">
										<button class="btn btn-sm btn-info">검색</button>
									</span>
								</div>
							</div>
							<a href="javascript:;" class="btn btn-sm btn-danger pull-right" onclick="checkedValue()"><span class="glyphicon glyphicon-remove"></span> 도서삭제</a>
							<a href="<?php echo admin_url('admin.php?page=church-library-create'); ?>" class="btn btn-sm btn-primary btn-r-margin pull-right"><span class="glyphicon glyphicon-plus"></span> 도서추가</a>
						</form>
					</div>
				</div>

			</div>
		</div>

		<table class="table table-hover">
			<thead>
			<tr>
				<th><input type="checkbox" id="checkall"></th>
				<th>제목</th>
				<th>저자</th>
				<th>보유권수</th>
				<th>출판사</th>
				<th>발행년도</th>
				<th>분류기호</th>
				<th>약호</th>
				<th>ISBN</th>
			</tr>
			</thead>
			<tbody>
				 <?php
					 foreach ( $results as $row ) {
						 printf( '
										<tr id="book_%s">
											<td><input type="checkbox" name="id[]" value="%d"</td>
											<td><a href="%s">%s</a></td>
											<td>%s</td>
											<td>%d</td>
											<td>%s</td>
											<td>%s</td>
											<td>%s</td>
											<td>%s</td>
											<td>%s</td>
										</tr>', $row -> id, $row -> id, admin_url("admin.php?page=church-library-create&action=edit&book_id={$row->id}"), $row -> title, $row -> writer, $row -> count, $row -> publisher, $row -> published_year, $row -> classified_code, $row -> book_code, $row -> isbn );
					 }
				 ?>
			</tbody>
		</table>
		 <?php echo $pagination -> parse() ?>
	</div>
</div>