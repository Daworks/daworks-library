<?php
	/**
	 * Created by PhpStorm.
	 * User: dhlee
	 * Date: 2017. 11. 3.
	 * Time: PM 3:00
	 */
	global $wpdb;
	$table = $wpdb->prefix . 'book_info';
	$query = "SELECT title, writer, publisher FROM {$table} ORDER BY created_at ASC, title ASC LIMIT %d";
	
	$books = $wpdb->get_results(
		$wpdb->prepare($query, $daworks_atts['nums'])
	);
?>

<table class="daworks_church_library_latest_table">
	<thead>
	<tr>
		<th>제목</th>
		<th>저자</th>
		<th>출판사</th>
	</tr>
	</thead>
	<tbody>
	<?php if ( count($books) > 0 ) : ?>
		<?php foreach($books as $book) : ?>
	<tr>
		<td><?php echo mb_substr($book->title, 1, 10) ?></td>
		<td><?php echo $book->writer ?></td>
		<td><?php echo $book->publisher ?></td>
	</tr>
			<?php endforeach ?>
	<?php else : ?>
		<tr>
			<td colspan="3" align="center">등록된 자료가 없습니다.</td>
		</tr>
	<?php endif ?>
	</tbody>
</table>
