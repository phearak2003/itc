import React from "react";
import { Link } from "react-router-dom";

const Header=()=>{
    return(<>
    <nav class="navbar navbar-expand-lg navbar-light header-container">
        <div class="container-fluid">
            <Link class="navbar-brand" to={'/'}>Blood Donation</Link>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse navbar-container" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <Link class="nav-link" aria-current="page" to={'/'}>Home</Link>
                <Link class="nav-link" to={'/our-team'}>Our Team</Link>
                <Link class="nav-link" to={'/about'}>About</Link>
                <Link class="nav-link" to={'/contact'}>Contact</Link>
            </div>
            </div>
        </div>
    </nav>
    </>)
}

export default Header;