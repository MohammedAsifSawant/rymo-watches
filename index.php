
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Rymo Watches</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

  <link rel="stylesheet" type="text/css"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">

  <link rel="stylesheet" href="style.css" />
</head>

<body>

  <!-- Navbar -->

  <nav class="navbar navbar-expand-lg navbar-light 
      py-3 fixed-top">
    <div class="container">
      <img src="images/logo1.png" alt="logo">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="true" aria-label="Toggle navigation">
        <span class="bar"><i class="navbar-toggler-icon"></i></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto ">

          <li class="nav-item">
            <a class="nav-link active" href="#">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="shop.php">Shop</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="website_products.php">Catalog</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="blog.php">Blog</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="feedback.php">Feedback</a>
          </li>
          <li class="nav-item">
    <a class="nav-link" href="contact.php">Contact</a>
</li>
<li class="nav-item">
    <a class="nav-link" href="profile.php">
        <i class="fa-solid fa-user"></i> My Profile
    </a>
</li>
          
          <!-- <i class="fa-solid fa-user"></i> -->
          
          <li class="nav-item">
            <i id="theme-toggle" class="fas fa-sun"></i>
            <a href="login.php"><i class="fa-solid fa-user"></i></a>
            <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i></a>
            
            <!-- <i class="fa-solid fa-magnifying-glass" onkeyup="searchwatch()" type="text" id="myInput"></i> -->
            <!-- <i class="fa-solid fa-bag-shopping"></i>  -->
          </li>
      </div>
    </div>
  </nav>

  <!-- . -->

  <form method="post" id="myform">

    <section id="home">
      <div class="container">
        <h5>NEW ARRIVALS</h5>
        <h1><span>Premium Watches</span> for Modern Style</h1>
        <p>Explore luxury, formal, smart, and sports watches with a cleaner shopping and management experience.</p>
        <input type="submit" value="Shop Now" formaction="shop.php" class="butt">
      </div>
    </section>

    <!-- brand -->
    <section id="brand" class="container">
      <div class="row m-0 py-5">
        <img class="img-fluid col-lg-2 col-md-4 col-6" src="images/brand/omega.png" alt="">

        <img class="img-fluid col-lg-2 col-md-4 col-6" src="images/brand/citizen.png" alt="">

        <img class="img-fluid col-lg-2 col-md-4 col-6" src="images/brand/Hublot.png" alt="">

        <img class="img-fluid col-lg-2 col-md-4 col-6" src="images/brand/Rolex.png" alt="">

        <img class="img-fluid col-lg-2 col-md-4 col-6" src="images/brand/Seiko.png" alt="">

        <img class="img-fluid col-lg-2 col-md-4 col-6" src="images/brand/Tag.png" alt="">
      </div>
    </section>

    <!--watchwear section-->
    <section id="watchwear" class="w-100">
      <div class="row p-0 m-0">
        <div class="one col-lg-4 col-md-12 col-12 p-0">
          <img class="img-fluid" src="images/watchwear/1.jpg" alt="watch">
          <div class="details">
            <h2>Digital Analog</h2>
            <button class="text-uppercase" formaction="shop.php">Shop now</button>
          </div>
        </div>
        <div class="one col-lg-4 col-md-12 col-12 p-0">
          <img class="img-fluid" src="images/watchwear/2.jpg" alt="watch">
          <div class="details">
            <h2>Smart Watches</h2>
            <button class="text-uppercase" formaction="shop.php">Shop now</button>
          </div>
        </div>
        <div class="one col-lg-4 col-md-12 col-12 p-0">
          <img class="img-fluid" src="images/watchwear/3.jpg" alt="watch">
          <div class="details">
            <h2>WatchWear Up To 50% off </h2>
            <button class="text-uppercase" formaction="shop.php">Shop now</button>
          </div>
        </div>
      </div>
    </section>

    <!-- a. -->
    <section id="featured" class="my-5 pb-5">
      <div class="container text-center mt-5 py-5">
        <h3>Our Featured</h3>
        <hr class="mx-auto">
        <p>Fresh watches from our sports, formal and smart collections.</p>
      </div>
      <!-- . -->

      <div class="row mx-auto container-fluid">
        <div class="product text-center col-lg-3 col-md-4 col-12">
          <img class="img-fluid mb-3" src="images/featured/1.jpg" alt="">
          <div class="star">
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
          </div>
          <h5 class="p-name">Sport Watch </h5>
          <h4 class="p-price">₹2230</h4>
          <button class="buy-btn" formaction="buy.php" name="a1">Buy Now</button>
          <!-- <input type="submit" value="Buy Now" formaction="buy.php" name="b1" class="buy-btn"> -->

        </div>

        <div class="product text-center col-lg-3 col-md-4 col-12">
          <img class="img-fluid mb-3" src="images/featured/2.jpg" alt="">
          <div class="star">
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
          </div>
          <h5 class="p-name">Formal Wear </h5>
          <h4 class="p-price">₹3499</h4>
          <button class="buy-btn" formaction="buy.php" name="a2">Buy Now</button>
        </div>

        <div class="product text-center col-lg-3 col-md-4 col-12">
          <img class="img-fluid mb-3" src="images/featured/3.jpg" alt="">
          <div class="star">
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
          </div>
          <h5 class="p-name">Smart Watch </h5>
          <h4 class="p-price">₹4500</h4>
          <button class="buy-btn" formaction="buy.php" name="a3">Buy Now</button>
        </div>

        <div class="product text-center col-lg-3 col-md-4 col-12">
          <img class="img-fluid mb-3" src="images/featured/4.jpg" alt="">
          <div class="star">
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
          </div>
          <h5 class="p-name">Curren</h5>
          <h4 class="p-price">₹1500</h4>
          <button class="buy-btn" formaction="buy.php" name="a4">Buy Now</button>
        </div>
      </div>

    </section>

    <!-- banner -->
    <section id="banner" class="my-5 py-5">
      <div class="container">
        <h4>MID SEASON"S SALE</h4>
        <h1>Season Collection <br>UP TO 20% OFF</h1>
        <button class="text-uppercase" formaction="shop.php">Shop Now</button>
      </div>
    </section>

    <!-- b. -->
    <section id="featuress" class="my-5 ">
      <div class="container text-center mt-5 py-5">
        <h3>NEW ARRIVALS</h3>
        <hr class="mx-auto">
        <p>Comfortable daily watches with clean designs.</p>
      </div>

      <div class="row mx-auto container-fluid">
        <div class="product text-center col-lg-3 col-md-4 col-12">
           <img class="img-fluid mb-3" src="images/wwatch/1.jpg" alt=""> 
          <div class="star">
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
          </div>
          <h5 class="p-name">Perfomance Gear </h5>
          <h4 class="p-price">₹4899</h4>
          <button class="buy-btn" formaction="buy.php" name="b1">Buy Now</button>
        </div>

        <div class="product text-center col-lg-3 col-md-4 col-12">
           <img class="img-fluid mb-3" src="images/wwatch/2.jpg" alt=""> 
          <div class="star">
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
          </div>
          <h5 class="p-name">Gentle Wear </h5>
          <h4 class="p-price">₹3499</h4>
          <button class="buy-btn" formaction="buy.php" name="b2">Buy Now</button>
        </div>

        <div class="product text-center col-lg-3 col-md-4 col-12">
          <img class="img-fluid mb-3" src="images/wwatch/3.jpg" alt="">
          <div class="star">
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
          </div>
          <h5 class="p-name"> Aura</h5>
          <h4 class="p-price">₹2999</h4>
          <button class="buy-btn" formaction="buy.php" name="b3">Buy Now</button>
        </div>

        <div class="product text-center col-lg-3 col-md-4 col-12">
          <img class="img-fluid mb-3" src="images/wwatch/4.jpg" alt="">
          <div class="star">
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
          </div>
          <h5 class="p-name">Rolex</h5>
          <h4 class="p-price">₹45000</h4>
          <button class="buy-btn" formaction="buy.php" name="b4">Buy Now</button>
        </div>
      </div>

    </section>

    <!-- c. -->
    <section id="watches" class="my-5  ">
      <div class="container text-center mt-5 py-5">
        <h3>G-Shock</h3>
        <hr class="mx-auto">
        <p>Smart and premium picks for regular use.</p>
      </div>

      <div class="row mx-auto container-fluid">
        <div class="product text-center col-lg-3 col-md-4 col-12">
          <img class="img-fluid mb-3" src="images/gshock/1.jpg" alt="">
          <div class="star">
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
          </div>
          <h5 class="p-name">MTG-B3000 </h5>
          <h4 class="p-price">₹8990</h4>
          <button class="buy-btn" formaction="buy.php" name="c1">Buy Now</button>
        </div>

        <div class="product text-center col-lg-3 col-md-4 col-12">
          <img class="img-fluid mb-3" src="images/gshock/2.jpg" alt="">
          <div class="star">
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
          </div>
          <h5 class="p-name">MTG-B3000 </h5>
          <h4 class="p-price">₹11000</h4>
          <button class="buy-btn" formaction="buy.php" name="c2">Buy Now</button>
        </div>

        <div class="product text-center col-lg-3 col-md-4 col-12">
          <img class="img-fluid mb-3" src="images/gshock/3.jpg" alt="">
          <div class="star">
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
          </div>
          <h5 class="p-name">GA-2100SRS </h5>
          <h4 class="p-price">₹7899</h4>
          <button class="buy-btn" formaction="buy.php" name="c3">Buy Now</button>
        </div>

        <div class="product text-center col-lg-3 col-md-4 col-12">
          <img class="img-fluid mb-3" src="images/gshock/4.jpg" alt="">
          <div class="star">
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
          </div>
          <h5 class="p-name">GA-700BNR</h5>
          <h4 class="p-price">₹14999</h4>
          <button class="buy-btn" formaction="buy.php" name="c4">Buy Now</button>
        </div>
      </div>

    </section>

    <!-- d. -->
    <section id="featured" class="my-5 pb-5">
      <div class="container text-center mt-5 py-5">
        <h3>luxury watches</h3>
        <hr class="mx-auto">
        <p>Selected customer favourites from the store.</p>
      </div>

      <div class="row mx-auto container-fluid">
        <div class="product text-center col-lg-3 col-md-4 col-12">
          <img class="img-fluid mb-3" src="images/premium/1.jpg" alt="">
          <div class="star">
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
          </div>
          <h5 class="p-name">Rolex </h5>
          <h4 class="p-price">₹39999</h4>
          <button class="buy-btn" formaction="buy.php" name="d1">Buy Now</button>
        </div>

        <div class="product text-center col-lg-3 col-md-4 col-12">
          <img class="img-fluid mb-3" src="images/premium/2.jpg" alt="">
          <div class="star">
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
          </div>
          <h5 class="p-name">Rado </h5>
          <h4 class="p-price">₹28999</h4>
          <button class="buy-btn" formaction="buy.php" name="d2">Buy Now</button>
        </div>

        <div class="product text-center col-lg-3 col-md-4 col-12">
          <img class="img-fluid mb-3" src="images/premium/3.jpg" alt="">
          <div class="star">
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
          </div>
          <h5 class="p-name">TAG Heuer</h5>
          <h4 class="p-price">₹49999</h4>
          <button class="buy-btn" formaction="buy.php" name="d3">Buy Now</button>
        </div>

        <div class="product text-center col-lg-3 col-md-4 col-12">
          <img class="img-fluid mb-3" src="images/premium/4.jpg" alt="">
          <div class="star">
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
          </div>
          <h5 class="p-name">Patek Philippe</h5>
          <h4 class="p-price">₹25000</h4>
          <button class="buy-btn" formaction="buy.php" name="d4">Buy Now</button>
        </div>
      </div>
    </section>

  </form>



  <!-- footer -->
  <footer class="mt-5 py-5">
    <div class="row container mx-auto pt-5">
      <div class="footer-one col-lg-3 col-md-6 col-12">
        <img src="images/fotter/logo2.png" alt="">
        <p class="pt-3">
          Shop For The Best Products. With Rymo.
        </p>
      </div>

      <div class="footer-one col-lg-3 col-md-6 col-12 mb-3">
        <h5 class="pb-2">Featured</h5>
        <ul class="text-uppercase list-unstyled">
          <li><a href="#">men</a></li>
          <li><a href="#">women</a></li>
          <li><a href="#">girls</a></li>
          <li><a href="#">boys</a></li>
          <li><a href="#">new arrivals</a></li>
          <li><a href="#">watches</a></li>
        </ul>
      </div>

      <div class="footer-one col-lg-3 col-md-6 col-12 mb-3">
        <h5 class="pb-2">Contact Us</h5>
        <div>
          <h6 class="text-uppercase">Address</h6>
          <p>411001, Camp, Pune </p>
        </div>
        <div>
          <h6 class="text-uppercase">Phone</h6>
          <p>(+91)8857995764 </p>
          <p>(+91)7385237820 </p>
        </div>
        <div>
          <h6 class="text-uppercase">Email</h6>
          <p>mohammedasifsawant93@gmail.com</p>
          <p>abdulrahimombilkar03@gmail.com</p>
        </div>
      </div>


      <div class="footer-one col-lg-3 col-md-6 col-12">
        <h5 class="pb-2">Instagram</h5>
        <div class="row">
          <img class="img-fluid w-25 h-100 m-2" src="images/insta/1.jpg" alt="">
          <img class="img-fluid w-25 h-100 m-2" src="images/insta/2.jpg" alt="">
          <img class="img-fluid w-25 h-100 m-2" src="images/insta/3.jpg" alt="">
          <img class="img-fluid w-25 h-100 m-2" src="images/insta/4.jpg" alt="">
          <img class="img-fluid w-25 h-100 m-2" src="images/insta/5.jpg" alt="">
        </div>
      </div>
    </div>


    <div class="copyright mt-5">
      <div class="row container mx-auto">

        <div class="col-lg-3 col-md-6 col-12 mb-4">
          <img src="images/payment.png" alt="">
        </div>

        <div class="col-lg-3 col-md-6 col-12 text-nowrap mb-2">
          <p>rymo eComm. © 2023. All Rights Reserved.</p>
        </div>

        <div class="col-lg-3 col-md-6 col-12 ">
          <a href="#"><i class="fa-brands fa-facebook"></i></a>
          <a href="#"><i class="fa-brands fa-twitter"></i></a>
          <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
        </div>
      </div>
    </div>
  </footer>










  <script>
    const themeToggle = document.getElementById("theme-toggle");
    const body = document.getElementsByTagName("body")[0];

    themeToggle.addEventListener("click", function () {
      body.classList.toggle("light-theme");
      body.classList.toggle("dark-theme");
    });
  </script>




  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3"
    crossorigin="anonymous"></script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
    integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V"
    crossorigin="anonymous"></script>
</body>

</html>
