<?php
session_start();
include("connection.php");

$current_time = date("M d, Y H:i:s");

if (isset($_COOKIE['uuid'])) {
    $u_uname = sanitize_input($_COOKIE['uuid']);
} else {
    $u_uname = '';
}

// Prepared statement to select from ods table
$sql4 = "SELECT * FROM ods WHERE uuid=? AND status='verified'";
$stmt4 = $link->prepare($sql4);
$stmt4->bind_param("s", $u_uname);
$stmt4->execute();
$result4 = $stmt4->get_result();

if ($result4->num_rows > 0) {
    while ($row4 = $result4->fetch_assoc()) {
        $u_uname = sanitize_input($row4['user_id']);
        $br_id = sanitize_input($row4['user_id']);
    }
} else {
    header("location:logout.php");
    exit;
}

include('header.php');
?>

<style>
    .blink_me {
        animation: blinker 1s linear infinite;
        color: white;
    }

    @keyframes blinker {
        50% {
            opacity: 0;
        }
    }

    .rainbow {
        text-align: center;
        text-decoration: none;
        font-size: 14px;
        font-family: monospace;
        letter-spacing: 0px;
    }
    .rainbow_text_animated {
        background: linear-gradient(to right, #6666ff, #0099ff , #00ff00, #ff3399, #6666ff);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        animation: rainbow_animation 6s ease-in-out infinite;
        background-size: 400% 100%;
    }

    @keyframes rainbow_animation {
        0%, 100% {
            background-position: 0 0;
        }
        50% {
            background-position: 100% 0;
        }
    }
</style>

<div class="page-wrapper">
    <div class="page-content">
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
            <?php
            $cards = [
                ["title" => "Total User", "query" => "SELECT * FROM user", "icon" => "bxs-cart"],
                ["title" => "Total Manager", "query" => "SELECT * FROM admin", "icon" => "bxs-cart"],
                ["title" => "Total Complaints", "query" => "SELECT * FROM complaint", "icon" => "bxs-cart"],
                ["title" => "Pending Complaints", "query" => "SELECT * FROM complaint WHERE category=1", "icon" => "bxs-cart"],
                ["title" => "Inprogress Complaints", "query" => "SELECT * FROM complaint WHERE category=2", "icon" => "bxs-cart"],
                ["title" => "Completed Complaints", "query" => "SELECT * FROM complaint WHERE category=3", "icon" => "bxs-cart"],
                ["title" => "Total Chat", "query" => "SELECT * FROM chat", "icon" => "bxs-cart"],
                ["title" => "Reviewed Complaints", "query" => "SELECT * FROM complaint WHERE review !='0'", "icon" => "bxs-cart"]
            ];

            foreach ($cards as $card) {
                $stmt = $link->prepare($card['query']);
                $stmt->execute();
                $result = $stmt->get_result();
                $count = $result->num_rows;
                $stmt->close();

                echo "<div class='col'>
                        <div class='card radius-10 border-start border-0 border-3 border-info'>
                            <div class='card-body'>
                                <div class='d-flex align-items-center'>
                                    <div>
                                        <p class='mb-0 text-secondary'>{$card['title']}</p>
                                        <h4 class='my-1 text-info'>{$count}</h4>
                                    </div>
                                    <div class='widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto'>
                                        <i class='bx {$card['icon']}'></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>";
            }
            ?>
        </div><!--end row-->
    </div>
</div>

<?php include('footer.php'); ?>
