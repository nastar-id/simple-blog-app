<style>
  * {
  margin: 0;
  padding: 0;
}

.container {
  max-width: 1224px;
  width: 90%;
  margin: auto;
  padding: 40px 0;
}

.title {
  margin-bottom: 2rem;
}

.photo-gallery {
  display: flex;
  gap: 20px;
}

.column {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.photo img {
  width: 100%;
  border-radius: 5px;
  height: 100%;
  object-fit: cover;
}

@media(max-width: 768px){
  .photo-gallery {
    flex-direction: column;
  }
}
</style>

<link rel="stylesheet" href="assets/css/style.css">

<?php
// Won't work
$desc = 'Line one\nline two';
// Should work
$desc2 = "Line one\r\nline two";

echo nl2br($desc);
echo '<br/><br>';
echo nl2br($desc2);
?>

<div class="container">
  <h2 class="title">Responsive Photo Gallery</h2>
  <div class="photo-gallery">
     <div class="column">
       <div class="photo">
          <img src="https://source.unsplash.com/OyCl7Y4y0Bk" alt="">
       </div>
       <div class="photo">
          <img src="https://source.unsplash.com/Kl1gC0ve620" alt="">
       </div>
       <div class="photo">
          <img src="https://source.unsplash.com/55btQzyDiO8" alt="">
       </div>
     </div>
     <div class="column">
       <div class="photo">
          <img src="https://source.unsplash.com/g0dBbrGmMe0" alt="">
       </div>
       <div class="photo">
          <img src="https://source.unsplash.com/v1OW17UcR-Q" alt="">
       </div>
       <div class="photo">
          <img src="https://source.unsplash.com/Wpg3Qm0zaGk" alt="">
       </div>
     </div>
     <div class="column">
       <div class="photo">
          <img src="https://source.unsplash.com/W3FC_bCPw8E" alt="">
       </div>
       <div class="photo">
          <img src="https://source.unsplash.com/rBRZLPVLQg0" alt="">
       </div>
       <div class="photo">
          <img src="https://source.unsplash.com/RRksEVVoU8o" alt="">
       </div>
     </div>
  </div>
</div>

<div class="grid">
  <div></div>
  <div></div>
  <div></div>
  <div></div>
  <div></div>
  <div></div>
  <div></div>
  <div></div>
  <div></div>
  <div></div>
  <div></div>
  <div></div>
</div>
<div class="grid">
  <div class="col-3"></div>
  <div class="col-3"></div>
  <div class="col-3"></div>
  <div class="col-3"></div>
  <div class="col-6"></div>
  <div class="col-6"></div>
  <div class="col-2 row-2"></div>
  <div class="col-8"></div>
  <div class="col-2 row-2"></div>
  <div class="col-3"></div>
  <div class="col-2"></div>
  <div class="col-3"></div>
</div>