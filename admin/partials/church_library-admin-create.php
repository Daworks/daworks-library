<?php
	/**
	 * Created by PhpStorm.
	 * User: dhlee
	 * Date: 2017. 11. 2.
	 * Time: PM 10:06
	 */
	$action = ( null !== $_REQUEST['action'] ) ? $_REQUEST['action'] : null;
	$book_id = ( null !== $_REQUEST['book_id'] ) ? $_REQUEST['book_id'] : null;
	
?>
<?php if (null === $action || $action !== 'edit' )  : ?>
<div class="row">
	<div class="col-sm-12">
		<h3 class="page-title">도서 추가</h3>
		<hr>
	</div>
	<div class="col-sm-6">
		<form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post" class="form-horizontal">
			<input type="hidden" name="action" value="church_library_store">
			<div class="form-group">
				<label for="title" class="control-label col-sm-2">도서명</label>
				<div class="col-sm-10">
					<input type="text" name="title" id="title" class="form-control input-sm" autofocus>
				</div>
			</div>
			<div class="form-group">
				<label for="writer" class="control-label col-sm-2">저자</label>
				<div class="col-sm-10">
					<input type="text" class="form-control input-sm" name="writer" id="writer">
				</div>
			</div>
			<div class="form-group">
				<label for="count" class="control-label col-sm-2">보유권수</label>
				<div class="col-sm-10">
					<input type="text" class="form-control input-sm" name="count" id="count">
				</div>
			</div>
			<div class="form-group">
				<label for="publisher" class="control-label col-sm-2">출판사</label>
				<div class="col-sm-10">
					<input type="text" class="form-control input-sm" name="publisher" id="publisher">
				</div>
			</div>
			<div class="form-group">
				<label for="published_year" class="control-label col-sm-2">발행년도</label>
				<div class="col-sm-10">
					<input type="text" class="form-control input-sm" name="published_year" id="published_year">
				</div>
			</div>
			<div class="form-group">
				<label for="classified_code" class="control-label col-sm-2">분류기호</label>
				<div class="col-sm-10">
					<input type="text" class="form-control input-sm" name="classified_code" id="classified_code">
				</div>
			</div>
			<div class="form-group">
				<label for="book_code" class="control-label col-sm-2">도서기호</label>
				<div class="col-sm-10">
					<input type="text" class="form-control input-sm" name="book_code" id="book_code">
				</div>
			</div>
			<div class="form-group">
				<label for="isbn" class="control-label col-sm-2">ISBN</label>
				<div class="col-sm-10">
					<input type="text" class="form-control input-sm" name="isbn" id="isbn">
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-10 col-sm-offset-2">
					<button class="btn btn-primary btn-size">저장</button>
					<a href="" class="btn btn-default btn-size">취소</a>
				</div>
			</div>
		</form>
	</div>
	<div class="col-sm-6">
	
	</div>
</div>
<?php else : ?>
	<?php include plugin_dir_path(__FILE__) . 'church_library-admin-edit.php'; ?>
<?php endif ?>
