import React, { useState } from "react";
import { useNavigate } from "react-router-dom";

const HospitalLogin = () => {
  const navigate = useNavigate();
  const [loginData, setLoginData] = useState({
    email: "",
    password: "",
  });
  const [error, setError] = useState("");

  // Handle input change
  const handleChange = (e) => {
    const { name, value } = e.target;
    setLoginData((prev) => ({
      ...prev,
      [name]: value,
    }));
  };

  // Handle form submission
  const handleSubmit = (e) => {
    e.preventDefault();

    const { email, password } = loginData;

    // Basic validation
    if (!email || !password) {
      setError("Please fill in all fields.");
      return;
    }

    // Clear previous errors
    setError("");
    console.log("Logging in with:", loginData);
    // You can add login API call here
  };

  // Simple Header component matching Home page
  const Header = () => (
    <header style={{
      backgroundColor: "#fff",
      boxShadow: "0 2px 4px rgba(0,0,0,0.1)",
      padding: "1rem",
      width: "100%"
    }}>
      <div style={{
        display: "flex",
        justifyContent: "space-between",
        alignItems: "center",
        maxWidth: "1200px",
        margin: "0 auto"
      }}>
        <div style={{ display: "flex", alignItems: "center" }}>
          <span style={{ color: "#dc2626", fontSize: "24px" }}>❤</span>
          <span style={{ marginLeft: "8px", fontWeight: "bold", fontSize: "18px" }}>BloodDonate</span>
        </div>
        <nav>
          <a href="/" style={{ margin: "0 10px", color: "#374151", textDecoration: "none" }}>Home</a>
          <a href="/about" style={{ margin: "0 10px", color: "#374151", textDecoration: "none" }}>About</a>
          <a href="/ourteam" style={{ margin: "0 10px", color: "#374151", textDecoration: "none" }}>OurTeam</a>
          <a href="/contact" style={{ margin: "0 10px", color: "#374151", textDecoration: "none" }}>Contact</a>
        </nav>
      </div>
    </header>
  );

  return (
    <div style={{
      display: "flex",
      flexDirection: "column",
      alignItems: "center",
      minHeight: "100vh",
      backgroundColor: "#f3f4f6",
      padding: "0 1.5rem"
    }}>
      {/* Include the Header */}
      <Header />
      
      {/* Login Container */}
      <div style={{
        width: "100%",
        maxWidth: "28rem",
        backgroundColor: "#ffffff",
        borderRadius: "0.5rem",
        padding: "2rem",
        marginTop: "3rem",
        boxShadow: "0 4px 12px rgba(0, 0, 0, 0.15)"
      }}>
        <h2 style={{
          fontSize: "1.5rem",
          fontWeight: "700",
          color: "#dc2626",
          marginBottom: "1.5rem",
          textAlign: "center"
        }}>Hospital Staff Login</h2>

        {error && (
          <div style={{
            backgroundColor: "#fee2e2",
            color: "#dc2626",
            padding: "0.75rem",
            borderRadius: "0.375rem",
            marginBottom: "1.5rem",
            fontSize: "0.875rem"
          }}>
            {error}
          </div>
        )}

        <form onSubmit={handleSubmit}>
          <div style={{ marginBottom: "1.25rem" }}>
            <input
              type="email"
              name="email"
              placeholder="Enter your email"
              value={loginData.email}
              onChange={handleChange}
              style={{
                width: "100%",
                padding: "0.75rem 1rem",
                borderRadius: "0.375rem",
                border: "1px solid #d1d5db",
                fontSize: "1rem",
                transition: "all 0.3s ease"
              }}
              required
            />
          </div>

          <div style={{ marginBottom: "1.75rem" }}>
            <input
              type="password"
              name="password"
              placeholder="Enter your password"
              value={loginData.password}
              onChange={handleChange}
              style={{
                width: "100%",
                padding: "0.75rem 1rem",
                borderRadius: "0.375rem",
                border: "1px solid #d1d5db",
                fontSize: "1rem",
                transition: "all 0.3s ease"
              }}
              required
            />
          </div>

          <button
            type="submit"
            style={{
              width: "100%",
              backgroundColor: "#dc2626",
              color: "#ffffff",
              padding: "0.875rem 0",
              borderRadius: "0.375rem",
              border: "none",
              fontSize: "1rem",
              fontWeight: "600",
              cursor: "pointer",
              transition: "background-color 0.3s ease, transform 0.3s ease",
              boxShadow: "0 4px 6px rgba(0,0,0,0.1)"
            }}
            onMouseOver={(e) => {
              e.currentTarget.style.backgroundColor = "#b91c1c";
              e.currentTarget.style.transform = "translateY(-2px)";
            }}
            onMouseOut={(e) => {
              e.currentTarget.style.backgroundColor = "#dc2626";
              e.currentTarget.style.transform = "translateY(0)";
            }}
          >
            Login
          </button>

          <div style={{
            display: "flex",
            justifyContent: "center",
            marginTop: "1.25rem"
          }}>
            <a
              href="#"
              style={{
                color: "#4b5563",
                fontSize: "0.875rem",
                textDecoration: "none",
                transition: "color 0.3s ease"
              }}
              onMouseOver={(e) => e.currentTarget.style.color = "#dc2626"}
              onMouseOut={(e) => e.currentTarget.style.color = "#4b5563"}
            >
              Forgot Password?
            </a>
          </div>
        </form>

        <div style={{
          marginTop: "2rem",
          textAlign: "center",
          borderTop: "1px solid #e5e7eb",
          paddingTop: "1.5rem"
        }}>
          <button
            onClick={() => navigate("/")}
            style={{
              backgroundColor: "transparent",
              color: "#4b5563",
              border: "1px solid #d1d5db",
              borderRadius: "0.375rem",
              padding: "0.625rem 1.25rem",
              fontSize: "0.875rem",
              cursor: "pointer",
              transition: "all 0.3s ease"
            }}
            onMouseOver={(e) => {
              e.currentTarget.style.backgroundColor = "#f3f4f6";
              e.currentTarget.style.borderColor = "#9ca3af";
            }}
            onMouseOut={(e) => {
              e.currentTarget.style.backgroundColor = "transparent";
              e.currentTarget.style.borderColor = "#d1d5db";
            }}
          >
            Back to Home
          </button>
        </div>
      </div>

      {/* Footer matching Home page */}
      <footer style={{
        width: "100%",
        backgroundColor: "#1f2937",
        color: "white",
        padding: "2rem 1.5rem",
        marginTop: "auto"
      }}>
        <div style={{
          maxWidth: "64rem",
          margin: "0 auto",
          display: "flex",
          flexDirection: "column",
          alignItems: "center"
        }}>
          <div style={{
            display: "flex",
            alignItems: "center",
            marginBottom: "1.5rem"
          }}>
            <span style={{ color: "#ef4444", fontSize: "24px" }}>❤</span>
            <span style={{ marginLeft: "8px", fontWeight: "bold", fontSize: "18px" }}>BloodDonate</span>
          </div>
          
          <div style={{
            marginBottom: "1.5rem",
            textAlign: "center"
          }}>
            <p>Connecting donors with those in need since 2023</p>
            <p style={{ marginTop: "0.5rem" }}>© 2025 BloodDonate. All rights reserved.</p>
          </div>
          
          <div style={{
            display: "flex",
            gap: "1rem"
          }}>
            <a href="#" style={{ color: "white", textDecoration: "none" }}>Privacy</a>
            <a href="#" style={{ color: "white", textDecoration: "none" }}>Terms</a>
            <a href="#" style={{ color: "white", textDecoration: "none" }}>Contact</a>
          </div>
        </div>
      </footer>
    </div>
  );
};

export default HospitalLogin;