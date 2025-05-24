import React from "react";
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';

const Banner = () => {
  return (
    <div id="carouselExampleControls" className="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
      <div className="carousel-inner">
        <div className="carousel-item active">
          <img 
            src="https://m.media-amazon.com/images/I/613BBkXBbTL._AC_UF894,1000_QL80_.jpg" 
            className="d-block w-100" 
            alt="Slide 1"
            style={{ height: '70vh', width: '70%', objectFit: 'contain', margin: 'auto' }}  
          />
        </div>
        <div className="carousel-item">
          <img 
            src="https://static.vecteezy.com/system/resources/previews/008/191/325/non_2x/blood-donation-symbol-with-hand-and-blood-bag-free-vector.jpg" 
            className="d-block w-100" 
            alt="Slide 2"
          />
        </div>
      </div>
      <button className="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
        <span className="carousel-control-prev-icon" aria-hidden="true"></span>
        <span className="visually-hidden">Previous</span>
      </button>
      <button className="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
        <span className="carousel-control-next-icon" aria-hidden="true"></span>
        <span className="visually-hidden">Next</span>
      </button>
    </div>
  );
};

export default Banner;
