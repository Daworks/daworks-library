<?php
	/**
	 * Created by PhpStorm.
	 * User: dhlee
	 * Date: 2017. 11. 3.
	 * Time: AM 12:20
	 */
	
	add_action('admin_bar_menu', 'add_church_library_toolbar_menu', 999);
	function add_church_library_toolbar_menu()
	{
		global $wp_admin_bar;
		$args = [
			'id' => 'add_new_book',
			'title' => '새 책 추가',
			'parent' => 'new-content',
			'href' => admin_url('admin.php?page=church-library-create'),
		];
		$wp_admin_bar->add_menu($args);
	}
	
	add_action( 'wp_ajax_getBookInfo', 'getBookInfo' );
	function getBookInfo ( $query )
	{
		$result = `curl -v -X GET "https://dapi.kakao.com/v2/search/book?target=title" \
								--data-urlencode "query={$query}" \
								-H "Authorization: KakaoAK f190946b1e1851958038c8487c1ad3bc"`;
		
		echo json_encode( $result );
		wp_die();
	}
	
	add_action( 'admin_post_church_library_store', 'church_library_store' );
	function church_library_store ()
	{
		global $wpdb;
		
		$post = $_POST;
		
		$title = sanitize_text_field( $post[ 'title' ] );
		$writer = sanitize_text_field( $post[ 'writer' ] );
		$count = sanitize_text_field( $post[ 'count' ] );
		$publisher = sanitize_text_field( $post[ 'publisher' ] );
		$published_year = sanitize_text_field( $post[ 'published_year' ] );
		$classified_code = sanitize_text_field( $post[ 'classified_code' ] );
		$book_code = sanitize_text_field( $post[ 'book_code' ] );
		$isbn = sanitize_text_field( $post[ 'isbn' ] );
		
		$table = $wpdb -> prefix . 'book_info';
		try {
			
			$wpdb -> insert(
				$table,
				array (
					'title'           => $title,
					'writer'          => $writer,
					'count'           => $count,
					'publisher'       => $publisher,
					'published_year'  => $published_year,
					'classified_code' => $classified_code,
					'book_code'       => $book_code,
					'isbn'            => $isbn
				),
				array ( '%s',
				        '%s',
				        '%d',
				        '%s',
				        '%s',
				        '%s',
				        '%s',
				        '%s' )
			);
			
			
			if ( !$wpdb->insert_id ) {
				throw new Exception( '입력 실패' );
			}
			$redirect = add_query_arg('msg', '저장 되었습니다.', admin_url('admin.php?page=church-library'));
			wp_redirect($redirect);
			
		}
		catch ( Exception $e ) {
			$error = $e->getMessage();
			include plugin_dir_path(__FILE__) . '../admin/partials/show-error.php';
		}
	}
	
	add_action('admin_post_church_library_update', 'church_library_update');
	function church_library_update()
	{
		try {
			global $wpdb;
			$id = (null !== $_REQUEST['book_id']) ? intval($_REQUEST['book_id']) : null;
			$table = $wpdb->prefix . 'book_info';
			
			
			$data = [
				'title' => sanitize_text_field( $_REQUEST['title']),
				'writer' => sanitize_text_field( $_REQUEST['writer']),
				'count' => sanitize_text_field( $_REQUEST['count']),
				'publisher' => sanitize_text_field( $_REQUEST['publisher']),
				'published_year' => sanitize_text_field( $_REQUEST['published_year']),
				'classified_code' => sanitize_text_field( $_REQUEST['classified_code']),
				'book_code' => sanitize_text_field( $_REQUEST['book_code']),
				'isbn' => sanitize_text_field( $_REQUEST['isbn'])
			];
			
			$result = $wpdb->update( $table, $data, array('id'=>$id));
			
			if ( false === $result ) throw new Exception('업데이트 실패');
			$wpdb->flush ();
			
			$redirect = add_query_arg('msg', '업데이트 되었습니다.', admin_url('admin.php?page=church-library'));
			wp_redirect($redirect);
		}
		catch (Exception $e)
		{
			$error = $e->getMessage();
			include plugin_dir_path(__FILE__) . '../admin/partials/show-error.php';
		}
	}
	
	add_action('wp_ajax_church_library_destroy', 'church_library_destroy');
	function church_library_destroy()
	{
		try
		{
			global $wpdb;
			
			$id = null !== $_POST['id'] ? $_POST['id'] : null;
			if ( null === $id ) throw new Exception('정상적인 경로로 접근하세요.');
			
			$table = $wpdb->prefix . 'book_info';
			$deleteRecord = $wpdb->delete($table, array('id'=>$id) );
			
			if ( false === $deleteRecord ) throw new Exception('삭제 중 오류 발생');
			
			echo json_encode([
				'status' => 'success',
				'id' => $deleteRecord
			                   ]);
			wp_die();
		}
		catch ( Exception $e )
		{
			$error = $e->getMessage ();
			
			echo json_encode([
				'status' => 'fail',
				'msg' => $error
			                   ]);
		}
	}
	
	function str_limit ( $str, $limit ) {
		$length       = mb_strlen ( $str );
		$return_value = $str;
		
		if ( $length > $limit ) {
			$return_value = mb_substr ( $str, 1, $limit ) . '...';
		}
		
		return $return_value;
	}