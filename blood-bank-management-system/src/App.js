import React from "react";
import { BrowserRouter, Routes, Route } from "react-router-dom";
import "bootstrap/dist/css/bootstrap.min.css";
import "jquery";
import "popper.js";
import "bootstrap/dist/js/bootstrap.min.js";
import "./App.css";

// Pages
import Home from "./pages/Home";
import OurTeam from "./pages/OurTeam";
import About from "./pages/About";
import Contact from "./pages/Contact";
import DonorSurvey from "./pages/DonorSurvey";
// import DonorAuth from "./pages/DonorAuth";
import HospitalLogin from "./pages/HospitalLogin";
import H_Dashboard from "./pages/H_Dashboard";
import AdminLogin from "./pages/AdminLogin";
import AdminDashboard from "./pages/AdminDashboard";
import DonorLanding from "./pages/DonorLanding";
import DonorRegister from "./pages/DonorRegister";
import DonorLogin from "./pages/DonorLogin";

function App() {
  return (
    <BrowserRouter>
      <Routes>
        {/* General Pages */}
        <Route path="/" element={<Home />} />
        <Route path="/ourteam" element={<OurTeam />} />
        <Route path="/about" element={<About />} />
        <Route path="/contact" element={<Contact />} />

        {/* Donor Flow */}
        <Route path="/donor" element={<DonorLanding />} /> {/* ✅ Fix added */}
        <Route path="/donor-landing" element={<DonorLanding />} />
        <Route path="/donor-register" element={<DonorRegister />} />
        <Route path="/donor-login" element={<DonorLogin />} />
        <Route path="/donor-survey" element={<DonorSurvey />} />

        {/* Hospital/Admin */}
        <Route path="/hospital-login" element={<HospitalLogin />} />
        <Route path="/hospital-dashboard" element={<H_Dashboard />} />
        <Route path="/admin-login" element={<AdminLogin />} />
        <Route path="/admin-dashboard" element={<AdminDashboard />} />
      </Routes>
    </BrowserRouter>
  );
}

export default App;
