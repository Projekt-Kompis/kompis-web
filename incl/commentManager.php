<?php
class CommentManager {
	public static function getAssemblyComments($db, $id){
		$username = $db->prepare("
			SELECT  a.*, c.*
			FROM assembly_comment a 
			    INNER JOIN assembly_comment_revision c
			        ON a.id = c.comment_id
			    INNER JOIN
			    (
			        SELECT comment_id, MAX(time_created) latest_time_created
			        FROM assembly_comment_revision
			        GROUP BY comment_id
			    ) b ON c.comment_id = b.comment_id AND
			            c.time_created = b.latest_time_created
			WHERE a.assembly_id = :id AND a.parent_assembly_comment_id IS NULL
		");
		$username->execute([':id' => $id]);
		return $username->fetchAll();
	}
}