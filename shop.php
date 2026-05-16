<?php
session_start();
if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
    echo "
<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8' />
    <meta http-equiv='X-UA-Compatible' content='IE=edge' />
    <meta name='viewport' content='width=device-width, initial-scale=1.0' />
    <title>Shop</title>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css'
        integrity='sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO' crossorigin='anonymous'>



    <link rel='stylesheet' type='text/css'
        href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css'>

    <link rel='stylesheet' href='style.css' />

    <style>
        .product img {
            width: 100%;
            height: auto;
            box-sizing: border-box;
            object-fit: cover;
        }

        #featured>div.row.mx-auto.container>nav>ul>li.page-item.active>a {
            background-color: black;
        }

        #featured>div.row.mx-auto.container>nav>ul>li:nth-child(n):hover>a {
            background-color: rgb(248, 154, 120);
            color: #fff;
        }

        .pagination a {
            color: #000;
        }
    </style>
</head>

<body>

    <!-- navigation bar    -->
    <nav class='navbar navbar-expand-lg navbar-light 
      py-3 fixed-top'>
        <div class='container'>
            <img src='images/logo1.png' alt='logo'>
            <button class='navbar-toggler' type='button' data-bs-toggle='collapse'
                data-bs-target='#navbarSupportedContent' aria-controls='navbarSupportedContent' aria-expanded='true'
                aria-label='Toggle navigation'>
                <span class='bar'><i class='navbar-toggler-icon'></i></span>
            </button>
            <div class='collapse navbar-collapse' id='navbarSupportedContent'>
                <ul class='navbar-nav ml-auto '>

                    <li class='nav-item'>
                        <a class='nav-link' href='index.php'>Home</a>
                    </li>
                    <li class='nav-item'>
                        <a class='nav-link active' href='#'>Shop</a>
                    </li>
                    <li class='nav-item'>
                        <a class='nav-link' href='website_products.php'>Catalog</a>
                    </li>
                    <li class='nav-item'>
                        <a class='nav-link' href='blog.php'>Blog</a>
                    </li>
                    <li class='nav-item'>
                        <a class='nav-link' href='feedback.php'>Feedback</a>
                    </li>
                    <li class='nav-item'>
    <a class='nav-link' href='contact.php'>Contact</a>
</li>
<li class='nav-item'>
    <a class='nav-link' href='profile.php'>
        <i class='fa-solid fa-user'></i> My Profile
    </a>
</li>
                    <li class='nav-item'>
                    <a href='logout.php'><i class='fa-solid fa-right-from-bracket'></i></a>
                    </li>
                
                    <!-- <li class='nav-item'>
                        <i class='fa-solid fa-magnifying-glass'></i>
                        <i class='fa-solid fa-bag-shopping'></i>
                    </li> -->
            </div>
        </div>
    </nav>

    <!-- . -->
    <form method='post'>
        <!-- section 1 -->
        <section id='featured' class='my-5 py-5'>
            <div class='container  mt-5 py-5'>
                <h2 class='font-weight-bold'>Featured Watches</h2>
                <hr>
                <p>Popular watches picked from sports, formal, smart and premium collections.</p>
            </div>

            <div class='row mx-auto container-fluid'>
                <!-- e -->
                <div class='product text-center col-lg-3 col-md-4 col-12'>
                    <!-- onclick='window.location.href='sproduct.html'' -->
                    <img class='img-fluid mb-3' src='images/rolex/1.jpg' alt=''>
                    <div class='star'>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                    </div>
                    <h5 class='p-name'>Deepsea Challenge </h5>
                    <h4 class='p-price'>₹69999</h4>
                    <button class='buy-btn' formaction='buy.php' name='e1'>Buy Now</button>
                </div>

                <div class='product text-center col-lg-3 col-md-4 col-12'>
                    <img class='img-fluid mb-3' src='images/rolex/2.jpg' alt=''>
                    <div class='star'>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                    </div>
                    <h5 class='p-name'>Sea-Dweller</h5>
                    <h4 class='p-price'>₹78999</h4>
                    <button class='buy-btn' formaction='buy.php' name='e2'>Buy Now</button>
                </div>

                <div class='product text-center col-lg-3 col-md-4 col-12'>
                    <img class='img-fluid mb-3' src='images/rolex/3.jpg' alt=''>
                    <div class='star'>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                    </div>
                    <h5 class='p-name'>Air-King </h5>
                    <h4 class='p-price'>₹50000</h4>
                    <button class='buy-btn' formaction='buy.php' name='e3'>Buy Now</button>
                </div>

                <div class='product text-center col-lg-3 col-md-4 col-12'>
                    <img class='img-fluid mb-3' src='images/rolex/4.jpg' alt=''>
                    <div class='star'>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                    </div>
                    <h5 class='p-name'>Night Jewel</h5>
                    <h4 class='p-price'>₹100000</h4>
                    <button class='buy-btn' formaction='buy.php' name='e4'>Buy Now</button>
                </div>

                <!-- f -->
                <div class='product text-center col-lg-3 col-md-4 col-12'>
                    <img class='img-fluid mb-3' src='images/rado/1.jpg' alt=''>
                    <div class='star'>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                    </div>
                    <h5 class='p-name'>Centrix</h5>
                    <h4 class='p-price'>₹44999</h4>
                    <button class='buy-btn' formaction='buy.php' name='f1'>Buy Now</button>
                </div>

                <div class='product text-center col-lg-3 col-md-4 col-12'>
                    <img class='img-fluid mb-3' src='images/rado/2.jpg' alt=''>
                    <div class='star'>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                    </div>
                    <h5 class='p-name'>High-Tech Ceramic Limited Edition </h5>
                    <h4 class='p-price'>₹33999</h4>
                    <button class='buy-btn' formaction='buy.php' name='f2'>Buy Now</button>
                </div>

                <div class='product text-center col-lg-3 col-md-4 col-12'>
                    <img class='img-fluid mb-3' src='images/rado/3.jpg' alt=''>
                    <div class='star'>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                    </div>
                    <h5 class='p-name'>DiaStar Original</h5>
                    <h4 class='p-price'>₹22399</h4>
                    <button class='buy-btn' formaction='buy.php' name='f3'>Buy Now</button>
                </div>

                <div class='product text-center col-lg-3 col-md-4 col-12'>
                    <img class='img-fluid mb-3' src='images/rado/4.jpg' alt=''>
                    <div class='star'>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                    </div>
                    <h5 class='p-name'>True Square</h5>
                    <h4 class='p-price'>₹45000</h4>
                    <button class='buy-btn' formaction='buy.php' name='f4'>Buy Now</button>
                </div>

                <!-- g -->
                <div class='product text-center col-lg-3 col-md-4 col-12'>
                    <img class='img-fluid mb-3' src='images/omega/1.jpg' alt=''>
                    <div class='star'>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                    </div>
                    <h5 class='p-name'>Trésor Quartz </h5>
                    <h4 class='p-price'>₹18999</h4>
                    <button class='buy-btn' formaction='buy.php' name='g1'>Buy Now</button>
                </div>

                <div class='product text-center col-lg-3 col-md-4 col-12'>
                    <img class='img-fluid mb-3' src='images/omega/2.jpg' alt=''>
                    <div class='star'>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                    </div>
                    <h5 class='p-name'>Moonwatch Professional </h5>
                    <h4 class='p-price'>₹65000</h4>
                    <button class='buy-btn' formaction='buy.php' name='g2'>Buy Now</button>
                </div>

                <div class='product text-center col-lg-3 col-md-4 col-12'>
                    <img class='img-fluid mb-3' src='images/omega/3.jpg' alt=''>
                    <div class='star'>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                    </div>
                    <h5 class='p-name'>Diver 300M Sea-Master </h5>
                    <h4 class='p-price'>₹82000</h4>
                    <button class='buy-btn' formaction='buy.php' name='g3'>Buy Now</button>
                </div>

                <div class='product text-center col-lg-3 col-md-4 col-12'>
                    <img class='img-fluid mb-3' src='images/omega/4.jpg' alt=''>
                    <div class='star'>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                    </div>
                    <h5 class='p-name'>Moonwatch Professional</h5>
                    <h4 class='p-price'>₹35000</h4>
                    <button class='buy-btn' formaction='buy.php' name='g4'>Buy Now</button>
                </div>

                <!-- h -->
                <div class='product text-center col-lg-3 col-md-4 col-12'>
                    <img class='img-fluid mb-3' src='images/citizen/1.jpg' alt=''>
                    <div class='star'>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                    </div>
                    <h5 class='p-name'>Citizen Eco-Drive Gents Watch </h5>
                    <h4 class='p-price'>₹16000</h4>
                    <button class='buy-btn' formaction='buy.php' name='h1'>Buy Now</button>
                </div>

                <div class='product text-center col-lg-3 col-md-4 col-12'>
                    <img class='img-fluid mb-3' src='images/citizen/2.jpg' alt=''>
                    <div class='star'>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                    </div>
                    <h5 class='p-name'>Citizen Automatic Gents Watch </h5>
                    <h4 class='p-price'>₹19999</h4>
                    <button class='buy-btn' formaction='buy.php' name='h2'>Buy Now</button>
                </div>

                <div class='product text-center col-lg-3 col-md-4 col-12'>
                    <img class='img-fluid mb-3' src='images/citizen/3.jpg' alt=''>
                    <div class='star'>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                    </div>
                    <h5 class='p-name'>Citizen Eco-Drive CA0617-11E </h5>
                    <h4 class='p-price'>₹16000</h4>
                    <button class='buy-btn' formaction='buy.php' name='h3'>Buy Now</button>
                </div>

                <div class='product text-center col-lg-3 col-md-4 col-12'>
                    <img class='img-fluid mb-3' src='images/citizen/4.jpg' alt=''>
                    <div class='star'>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                    </div>
                    <h5 class='p-name'>Citizen Eco-Drive Gents Watch</h5>
                    <h4 class='p-price'>₹24999</h4>
                    <button class='buy-btn' formaction='buy.php' name='h4'>Buy Now</button>
                </div>

                <!-- i -->
                <div class='product text-center col-lg-3 col-md-4 col-12'>
                    <img class='img-fluid mb-3' src='images/hublot/1.jpg' alt=''>
                    <div class='star'>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                    </div>
                    <h5 class='p-name'>MP-09 Tourbillon Bi-Axis Red </h5>
                    <h4 class='p-price'>₹320000</h4>
                    <button class='buy-btn' formaction='buy.php' name='i1'>Buy Now</button>
                </div>

                <div class='product text-center col-lg-3 col-md-4 col-12'>
                    <img class='img-fluid mb-3' src='images/hublot/2.jpg' alt=''>
                    <div class='star'>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                    </div>
                    <h5 class='p-name'>Unico SORAI </h5>
                    <h4 class='p-price'>₹18000</h4>
                    <button class='buy-btn' formaction='buy.php' name='i2'>Buy Now</button>
                </div>

                <div class='product text-center col-lg-3 col-md-4 col-12'>
                    <img class='img-fluid mb-3' src='images/hublot/3.jpg' alt=''>
                    <div class='star'>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                    </div>
                    <h5 class='p-name'>Tourbillon Bi-Axis</h5>
                    <h4 class='p-price'>₹13999</h4>
                    <button class='buy-btn' formaction='buy.php' name='i3'>Buy Now</button>
                </div>

                <div class='product text-center col-lg-3 col-md-4 col-12'>
                    <img class='img-fluid mb-3' src='images/hublot/4.jpg' alt=''>
                    <div class='star'>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                    </div>
                    <h5 class='p-name'>Titanium Ceramic</h5>
                    <h4 class='p-price'>₹16999</h4>
                    <button class='buy-btn' formaction='buy.php' name='i4'>Buy Now</button>
                </div>

                <!-- j -->
                <div class='product text-center col-lg-3 col-md-4 col-12'>
                    <img class='img-fluid mb-3' src='images/smart/1.jpg' alt=''>
                    <div class='star'>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                    </div>
                    <h5 class='p-name'>ID116 Plus</h5>
                    <h4 class='p-price'>₹3500</h4>
                    <button class='buy-btn' formaction='buy.php' name='j1'>Buy Now</button>
                </div>

                <div class='product text-center col-lg-3 col-md-4 col-12'>
                    <img class='img-fluid mb-3' src='images/smart/2.jpg' alt=''>
                    <div class='star'>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                    </div>
                    <h5 class='p-name'>Gladiator</h5>
                    <h4 class='p-price'>₹3000</h4>
                    <button class='buy-btn' formaction='buy.php' name='j2'>Buy Now</button>
                </div>

                <div class='product text-center col-lg-3 col-md-4 col-12'>
                    <img class='img-fluid mb-3' src='images/smart/3.jpg' alt=''>
                    <div class='star'>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                    </div>
                    <h5 class='p-name'>Super Nova </h5>
                    <h4 class='p-price'>₹7900</h4>
                    <button class='buy-btn' formaction='buy.php' name='j3'>Buy Now</button>
                </div>

                <div class='product text-center col-lg-3 col-md-4 col-12'>
                    <img class='img-fluid mb-3' src='images/smart/4.jpg' alt=''>
                    <div class='star'>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                        <i class='fa-solid fa-star'></i>
                    </div>
                    <h5 class='p-name'>Ninja 3 Smartwatch</h5>
                    <h4 class='p-price'>₹3200</h4>
                    <button class='buy-btn' formaction='buy.php' name='j4'>Buy Now</button>
                </div>

                <!-- page shifting -->
                <!-- <nav aria-label='Page navigation example'>
                <ul class='pagination mt-5'>
                  <li class='page-item'><a class='page-link' href='#'>Previous</a></li>
                  <li class='page-item active'><a class='page-link' href='#'>1</a></li>
                  <li class='page-item'><a class='page-link' href='#'>2</a></li>
                  <li class='page-item'><a class='page-link' href='#'>3</a></li>
                  <li class='page-item'><a class='page-link' href='#'>Next</a></li>
                </ul>
              </nav> -->
            </div>
        </section>
    </form>


    <!-- footer -->
    <footer class='mt-5 py-5'>
        <div class='row container mx-auto pt-5'>
            <div class='footer-one col-lg-3 col-md-6 col-12'>
                <img src='images/fotter/logo2.png' alt=''>
                <p class='pt-3'>
                    Shop For The Best Products. With Rymo.
                </p>
            </div>

            <div class='footer-one col-lg-3 col-md-6 col-12 mb-3'>
                <h5 class='pb-2'>Featured</h5>
                <ul class='text-uppercase list-unstyled'>
                    <li><a href='#'>men</a></li>
                    <li><a href='#'>women</a></li>
                    <li><a href='#'>girls</a></li>
                    <li><a href='#'>boys</a></li>
                    <li><a href='#'>new arrivals</a></li>
                    <li><a href='#'>watches</a></li>
                </ul>
            </div>

            <div class='footer-one col-lg-3 col-md-6 col-12 mb-3'>
                <h5 class='pb-2'>Contact Us</h5>
                <div>
                    <h6 class='text-uppercase'>Address</h6>
                    <p>411001, Camp, Pune </p>
                </div>
                <div>
                    <h6 class='text-uppercase'>Phone</h6>
                    <p>(+91)8857995764 </p>
                    <p>(+91)7385237820 </p>
                </div>
                <div>
                    <h6 class='text-uppercase'>Email</h6>
                    <p>mohammedasifsawant93@gmail.com</p>
                    <p>abdulrahimombilkar03@gmail.com</p>
                </div>
            </div>


            <div class='footer-one col-lg-3 col-md-6 col-12'>
                <h5 class='pb-2'>Instagram</h5>
                <div class='row'>
                    <img class='img-fluid w-25 h-100 m-2' src='images/insta/1.jpg' alt=''>
                    <img class='img-fluid w-25 h-100 m-2' src='images/insta/2.jpg' alt=''>
                    <img class='img-fluid w-25 h-100 m-2' src='images/insta/3.jpg' alt=''>
                    <img class='img-fluid w-25 h-100 m-2' src='images/insta/4.jpg' alt=''>
                    <img class='img-fluid w-25 h-100 m-2' src='images/insta/5.jpg' alt=''>
                </div>
            </div>
        </div>


        <div class='copyright mt-5'>
            <div class='row container mx-auto'>

                <div class='col-lg-3 col-md-6 col-12 mb-4'>
                    <img src='images/payment.png' alt=''>
                </div>

                <div class='col-lg-3 col-md-6 col-12 text-nowrap mb-2'>
                    <p>rymo eComm. © 2023. All Rights Reserved.</p>
                </div>

                <div class='col-lg-3 col-md-6 col-12 '>
                    <a href='#'><i class='fa-brands fa-facebook'></i></a>
                    <a href='#'><i class='fa-brands fa-twitter'></i></a>
                    <a href='#'><i class='fa-brands fa-linkedin-in'></i></a>
                </div>
            </div>
        </div>
    </footer>





    <script src='https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js'
        integrity='sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3'
        crossorigin='anonymous'></script>
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js'
        integrity='sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V'
        crossorigin='anonymous'></script>
</body>

</html>
";
} else {
    echo '<script language = "javascript">';
    echo 'alert("Fail login")';
    echo '</script>';
    echo "<script>window.location.assign('login.php')</script>";
    die();
}

?>
