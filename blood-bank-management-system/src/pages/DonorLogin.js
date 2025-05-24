import React, { useState } from "react";
import { useNavigate } from "react-router-dom";

const DonorLogin = () => {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [isLoading, setIsLoading] = useState(false);
  const navigate = useNavigate();

  const handleLogin = async (e) => {
    e.preventDefault();
    
    if (!email || !password) {
      alert("Please enter valid credentials.");
      return;
    }
    
    try {
      setIsLoading(true);
      // Simulate authentication delay
      await new Promise(resolve => setTimeout(resolve, 800));
      
      // Dummy auth check - replace with actual authentication
      navigate("/donor-survey");
    } catch (err) {
      alert("Authentication failed. Please try again.");
    } finally {
      setIsLoading(false);
    }
  };

  // Simple Header component
  const Header = () => (
    <header style={{
      width: "100%",
      backgroundColor: "white",
      boxShadow: "0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)",
      padding: "1rem"
    }}>
      <div style={{
        display: "flex",
        justifyContent: "space-between",
        alignItems: "center",
        maxWidth: "80rem",
        margin: "0 auto"
      }}>
        <div style={{ display: "flex", alignItems: "center" }}>
          <span style={{ color: "#dc2626", fontSize: "1.5rem" }}>❤</span>
          <span style={{ marginLeft: "0.5rem", fontWeight: "bold", fontSize: "1.125rem" }}>BloodDonate</span>
        </div>
        <nav>
          <a href="/" style={{ 
            margin: "0 0.625rem", 
            color: "#dc2626", 
            fontWeight: "500", 
            textDecoration: "none", 
            borderBottom: "2px solid #dc2626", 
            paddingBottom: "0.375rem" 
          }}>Home</a>
          <a href="/about" style={{ margin: "0 0.625rem", color: "#374151", textDecoration: "none" }}>About</a>
          <a href="/ourteam" style={{ margin: "0 0.625rem", color: "#374151", textDecoration: "none" }}>OurTeam</a>
          <a href="/contact" style={{ margin: "0 0.625rem", color: "#374151", textDecoration: "none" }}>Contact</a>
        </nav>
      </div>
    </header>
  );

  return (
    <div style={{
      display: "flex",
      flexDirection: "column",
      alignItems: "center",
      justifyContent: "center",
      minHeight: "100vh",
      backgroundColor: "#f3f4f6",
      padding: "0 1.5rem"
    }}>
      {/* Top Components */}
      <Header />

      {/* Banner */}
      <div style={{
        width: "100%",
        maxWidth: "80rem",
        background: "linear-gradient(to right, #fee2e2, #fecaca)",
        borderRadius: "0.5rem",
        padding: "2rem",
        margin: "2rem 0",
        boxShadow: "0 4px 6px -1px rgba(0, 0, 0, 0.1)"
      }}>
        <div style={{
          display: "flex",
          flexDirection: "column",
          alignItems: "center",
          justifyContent: "center",
          textAlign: "center"
        }}>
          <h2 style={{
            fontSize: "1.25rem",
            fontWeight: "600",
            color: "#dc2626",
            marginBottom: "1rem"
          }}>Welcome Back Donor! ❤️</h2>
          <p style={{
            fontSize: "1.125rem",
            color: "#4b5563",
            maxWidth: "48rem"
          }}>
            Login to access your donation history, schedule your next appointment, 
            and continue your journey of saving lives through blood donation.
          </p>
        </div>
      </div>

      {/* Main Content */}
      <div style={{
        width: "100%",
        maxWidth: "28rem",
        backgroundColor: "white",
        borderRadius: "0.5rem",
        boxShadow: "0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)",
        padding: "2rem",
        marginBottom: "2rem"
      }}>
        <div style={{
          display: "flex",
          flexDirection: "column",
          alignItems: "center"
        }}>
          <span style={{ color: "#dc2626", fontSize: "1.875rem" }}>❤</span>
          <h2 style={{
            marginTop: "1.5rem",
            textAlign: "center",
            fontSize: "1.875rem",
            fontWeight: "bold",
            color: "#111827"
          }}>
            Donor Login
          </h2>
          <p style={{
            marginTop: "0.5rem",
            textAlign: "center",
            fontSize: "1.125rem",
            color: "#4b5563"
          }}>
            Access your donor dashboard
          </p>
        </div>

        <form style={{ marginTop: "2rem" }} onSubmit={handleLogin}>
          <div style={{ marginBottom: "1.5rem" }}>
            <label htmlFor="email" style={{
              display: "block",
              fontSize: "0.875rem",
              fontWeight: "500",
              color: "#374151"
            }}>
              Email address
            </label>
            <input
              id="email"
              name="email"
              type="email"
              autoComplete="email"
              required
              style={{
                marginTop: "0.25rem",
                display: "block",
                width: "100%",
                borderRadius: "0.375rem",
                border: "1px solid #d1d5db",
                padding: "0.75rem 1rem",
                color: "#111827",
                outline: "none"
              }}
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              placeholder="Enter your email"
            />
          </div>

          <div style={{ marginBottom: "1.5rem" }}>
            <div style={{
              display: "flex",
              alignItems: "center",
              justifyContent: "space-between"
            }}>
              <label htmlFor="password" style={{
                display: "block",
                fontSize: "0.875rem",
                fontWeight: "500",
                color: "#374151"
              }}>
                Password
              </label>
              <a href="#" style={{
                fontSize: "0.875rem",
                fontWeight: "500",
                color: "#dc2626",
                textDecoration: "none"
              }}>
                Forgot password?
              </a>
            </div>
            <input
              id="password"
              name="password"
              type="password"
              autoComplete="current-password"
              required
              style={{
                marginTop: "0.25rem",
                display: "block",
                width: "100%",
                borderRadius: "0.375rem",
                border: "1px solid #d1d5db",
                padding: "0.75rem 1rem",
                color: "#111827",
                outline: "none"
              }}
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              placeholder="Enter your password"
            />
          </div>

          <div>
            <button
              type="submit"
              disabled={isLoading}
              style={{
                width: "100%",
                backgroundColor: "#dc2626",
                color: "black",
                padding: "0.75rem 1rem",
                borderRadius: "0.75rem",
                fontSize: "1.125rem",
                fontWeight: "600",
                boxShadow: "0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)",
                cursor: "pointer",
                border: "none",
                transition: "transform 0.3s ease-in-out"
              }}
              onMouseOver={(e) => e.currentTarget.style.transform = "scale(1.1)"}
              onMouseOut={(e) => e.currentTarget.style.transform = "scale(1)"}
            >
              {isLoading ? "Logging in..." : "Login"}
            </button>
          </div>
        </form>

        <p style={{
          marginTop: "2rem",
          textAlign: "center",
          fontSize: "1rem",
          color: "#6b7280"
        }}>
          Not registered yet?{" "}
          <a href="#" style={{
            fontWeight: "600",
            color: "#dc2626",
            textDecoration: "none"
          }}>
            Register as a donor
          </a>
        </p>
      </div>

      {/* Footer */}
      <footer style={{
        width: "100%",
        backgroundColor: "#1f2937",
        color: "white",
        padding: "2rem 1.5rem",
        marginTop: "auto"
      }}>
        <div style={{
          maxWidth: "80rem",
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
            <span style={{ color: "#ef4444", fontSize: "1.5rem" }}>❤</span>
            <span style={{ marginLeft: "0.5rem", fontWeight: "bold", fontSize: "1.125rem" }}>BloodDonate</span>
          </div>
          
          <div style={{
            marginBottom: "1.5rem",
            textAlign: "center"
          }}>
            <p>Connecting donors with those in need since 2023</p>
            <p style={{ marginTop: "0.5rem" }}>© 2025 BloodDonate. All rights reserved.</p>
          </div>
          
          <div style={{ display: "flex", gap: "1rem" }}>
            <a href="#" style={{ color: "white", textDecoration: "none" }}>Privacy</a>
            <a href="#" style={{ color: "white", textDecoration: "none" }}>Terms</a>
            <a href="#" style={{ color: "white", textDecoration: "none" }}>Contact</a>
          </div>
        </div>
      </footer>
    </div>
  );
};

export default DonorLogin;