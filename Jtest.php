
<?php
    
    function opencon(){
        $dbhost = "localhost";
        $db = "iwp";
        $dbuser = "root";
        $dbpwd = "";

        $conn = new mysqli($dbhost,$dbuser,$dbpwd,$db)
            or die("conn failed ".conn->error);

        return $conn;    
    }

    function closecon($conn){
        $conn->close();
        
    }

    function searchRes($con,$searched,$cust){

        $sql = "select NAME,CUST from restaurents where NAME like ? and CUST in ";

        $likesearched = '%'.$searched.'%';

        if ($cust=='b' or $cust=='g') {
            $sql.="(?, 'u')";
           
             }
        else if ($cust=='u') {
            $sql.= "(?)";
        }
        else{
            $sql.="('g','b','u',?)";
        }

        $stmt = $con->prepare($sql);
        
        $stmt->bind_param('ss',$likesearched,$cust);
    
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows>0){
        while($row = $result->fetch_assoc()){
            echo '<div class="search-div"> <span class="dname">'.$row['NAME'].'</span>    <span class="price">'.$row['CUST'].'</span></div>';
        }
    }
    else{
        echo "<p>no match found</p>";
    }
    }

    function searchDish($con,$searched,$cust){

        $sql = "select M.DNAME,M.PRICE,R.CUST,R.NAME FROM 
                                RESTAURENTS R JOIN MENU M ON
                                R.ID=M.RID WHERE M.DNAME LIKE ? AND R.CUST IN ";
        
        $likesearched = '%'.$searched.'%';

        if ($cust=='b' or $cust=='g') {
            $sql.="(?, 'u')";
           
             }
        else if ($cust=='u') {
            $sql.= "(?)";
        }
        else{
            $sql.="('g','b','u',?)";
        }

                                
        $stmt = $con->prepare($sql);
        $stmt->bind_param('ss',$likesearched,$cust);
 
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows>0){
        while($row = $result->fetch_assoc()){
            echo '<div class="search-div"><span class="dname"> '.$row['DNAME'].'</span><span class="price">  '.$row['PRICE'].'</span><span class="cust">  '
                    .$row['CUST'].'</span><span class="name">   '.$row['NAME'].'</span></div>';
        }
    }
    else{
        echo "<p>no match found</p>";
    }
    }

    function searchfunc(){

    $con = opencon();
    $searched = $_POST['searched'];
    $cust = $_POST['cust'];

    echo "<h2>Restaurents</h2>";
    searchRes($con,$searched,$cust);

    echo "<h2>Dishes</h2>";
    searchDish($con,$searched,$cust);
    

    closecon($con);
    }


 function resinfo(){
        $con = opencon();
        $rid = $_POST['rid'];

        $sql = "select DNAME,PRICE from MENU where rid=?";
        

        $stmt = $con->prepare($sql);
        
        $stmt->bind_param('i',$rid);
     
    $stmt->execute();
    
    $result = $stmt->get_result();
    if($result->num_rows>0){
        echo '<h2 id="menuhead">MENU</h2>';
        while($row = $result->fetch_assoc()){
            echo '<div class="search-div"><span class="dname"> '.$row['DNAME'].'</span>   <span class="price"> '.$row['PRICE'].'</span></div>';
        }
    }
    else{
        echo "<p>no items</p>";
    }

    closecon($con);

   
    }

    function signup(){
        $con = opencon();

        $stmt = $con->prepare("INSERT INTO users(pname, uname, pwd) VALUES (?, ?, ?)");
        $stmt->bind_param('sss',$pname, $uname, $hpwd);

        $pname= $_POST['pname'];
        $uname = $_POST['username'];
        $pwd = $_POST['password'];

        $hpwd = password_hash($pwd, PASSWORD_DEFAULT);

        $sqlcheck = "select uname from users where uname=?";
        

        $stmtcheck = $con->prepare($sqlcheck);
        
        $stmtcheck->bind_param('s',$uname);
     
    $stmtcheck->execute();
    
    $result = $stmtcheck->get_result();
    if($result->num_rows>0){
        echo "<h3>use a different username</h3><br><a href='signup.html'>signup</a>";
        return;
    }

  if ($stmt->execute()) {
    echo "Signup successful!";
        session_start();
        $_SESSION['pname']=$pname;
        header("Location: Search.html");

  } else {
    echo "<h3>use a different username<\h3><br><a href='signup.html'>signup<\a>";
  }

        closecon($con);

    }

    function logIn(){

        $con = opencon();
        
        $uname = $_POST['username'];
        $pwd = $_POST['password'];

        $stmt = $con->prepare("SELECT * FROM users WHERE uname=?");
        $stmt->bind_param("s", $uname);
    
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                if (password_verify($pwd, $row['pwd'])) {
                    
                    session_start();

                    $_SESSION['pname'] = $row['pname'];
                    $_SESSION['uname'] = $row['uname'];
                    header("Location: Search.html");
                }
                else{
                    echo "invalid password";
                }
            }
            else{
                echo "User not found";
            }
        }
        else{
            echo "query error";
        }

        closecon($con);

    }


    if(isset($_POST['action'])){
        if ($_POST['action'] == 'resinfo') {
        resinfo();
      } 

      if ($_POST['action'] == 'searchfunc') {
        searchfunc();
      } 
      

      if($_POST['action']=='signup'){
        signup();
      }

      
      if($_POST['action']=='login'){
        login();
      }

      if ($_POST['action'] == 'login') {
        logIn();
      } 

    }
    else{
        echo "Wrong action";
    }
      
?>