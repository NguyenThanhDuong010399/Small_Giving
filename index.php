<?php 
	class Dangky{
		public $Email;
		public $SDT;
		public $MatKhau;
		private $conn;
		public function __construct($db){
        $this->conn = $db;}
		function Luutaikhoan()
		{
			$query="INSERT INTO tblnguoidung SET Email=:Email,SDT=:SDT,MatKhau=:MatKhau";
			$stmt=$this->conn->prepare($query);
			$this->Email=htmlspecialchars(strip_tags($this->Email));
			$this->SDT=htmlspecialchars(strip_tags($this->SDT));
			$this->MatKhau=htmlspecialchars(strip_tags($this->MatKhau));

			$stmt->bindParam(":Email", $this->Email);
		    $stmt->bindParam(":SDT", $this->SDT);
		    $stmt->bindParam(":MatKhau", $this->MatKhau);
		    // $stmt->bindParam(":category_id", $this->category_id);
		    // $stmt->bindParam(":created", $this->created);
		 
		    // execute query
		    if($stmt->execute()){
		        return true;
		    }
		 
		    return false;
		}
		function kiemtra()
		{
			$query="SELECT SDT from tblnguoidung where SDT=? LIMIT 0,1";
			$stmt =$this->conn->prepare($query);

			$stmt->bindParam(1,$this->SDT);
			$stmt->execute();
			$row=$stmt->fetch(PDO::FETCH_ASSOC);

			// $this->Email=$row['Email'];
			$this->SDT=$row['SDT'];
			// $this->MatKhau=$row['MatKhau'];
		}
	}
	class Dangnhap
	{
	public $Email;
	public $SDT;
	public $MatKhau;
	private $conn;
	public function __construct($db){
        $this->conn = $db;}
	function Login(){
		$query="SELECT Email,MatKhau from tblnguoidung where (Email=? and MatKhau=?) or (SDT=? and MatKhau=?) LIMIT 0,1";
		$stmt=$this->conn->prepare($query);

		$stmt->bindParam(1,$this->Email);
		$stmt->bindParam(1,$this->MatKhau);
		$stmt->bindParam(1,$this->SDT);

		$stmt->execute();

		$row=$stmt->fetch(PDO::FETCH_ASSOC);
		$this->Email=$row['Email'];
		$this->MatKhau=$row['MatKhau'];
		$this->MatKhau=$row['SDT'];
	}
	function Nhopass()
	{
		$query="SELECT Email,MatKhau from tblnguoidung where id=? LIMIT 0,1";
		
		$stmt=$this->conn->prepare($query);

		$stmt->bindParam(1,$this->id);
		//$stmt->bindParam(1,$this->SDT);

		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);
 
	    // set values to object properties
	    //$this->id = $row['id'];
	    $this->Email = $row['Email'];
	    //$this->SDT = $row['SDT'];
	    $this->MatKhau = $row['MatKhau'];

	}
	function Quenpass()
	{
		$query="SELECT Email from tblnguoidung where id=? LIMIT 0,1";
		$stmt=$this->conn->prepare($query);

		$stmt->bindParam(1,$this->id);
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->Email=$row['Email'];
	}
	function Doipass()
	{
		$query="UPDATE tblnguoidung SET MatKhau=:MatKhau where id=:id";

		$stmt = $this->conn->prepare($query);
		$this->MatKhau=htmlspecialchars(strip_tags($this->MatKhau));
		$this->id=htmlspecialchars(strip_tags($this->id));
		

		
		$stmt->bindParam(':MatKhau',$this->MatKhau);
		$stmt->bindParam(':id',$this->id);

		if($stmt->execute())
		{
			return true;
		}
		return false;
	}
}
?>