import React, { useState } from "react";
import { useNavigate } from "react-router-dom";

const DonorRegister = () => {
  const [formData, setFormData] = useState({
    fullName: "",
    email: "",
    password: "",
    confirmPassword: "",
    phone: "",
    dateOfBirth: "",
    bloodType: "",
    address: "",
    city: "",
    state: "",
    zipCode: ""
  });

  const navigate = useNavigate();

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData({
      ...formData,
      [name]: value
    });
  };

  const handleRegister = (e) => {
    e.preventDefault();

    // Simple validation
    const requiredFields = ['fullName', 'email', 'password', 'confirmPassword', 'phone', 'dateOfBirth', 'bloodType'];
    const missingFields = requiredFields.filter(field => !formData[field]);
    
    if (missingFields.length > 0) {
      alert("Please fill in all required fields.");
      return;
    }

    if (formData.password !== formData.confirmPassword) {
      alert("Passwords do not match.");
      return;
    }

    // You can replace this with actual registration logic
    console.log("Registration data:", formData);
    navigate("/donor-survey");
  };

  // Simple Header component
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
          <a href="/" style={{ 
            margin: "0 10px", 
            color: "#dc2626", 
            fontWeight: "500", 
            textDecoration: "none",
            borderBottom: "2px solid #dc2626",
            paddingBottom: "5px"
          }}>Home</a>
          <a href="/about" style={{ margin: "0 10px", color: "#374151", textDecoration: "none" }}>About</a>
          <a href="/ourteam" style={{ margin: "0 10px", color: "#374151", textDecoration: "none" }}>OurTeam</a>
          <a href="/contact" style={{ margin: "0 10px", color: "#374151", textDecoration: "none" }}>Contact</a>
        </nav>
      </div>
    </header>
  );

  // Banner Component
  const Banner = () => (
    <div style={{
      width: "100%",
      maxWidth: "1200px",
      background: "linear-gradient(to right, #fee2e2, #fecaca)",
      borderRadius: "8px",
      padding: "2rem",
      margin: "2rem 0",
      boxShadow: "0 4px 6px rgba(0,0,0,0.1)"
    }}>
      <div style={{
        display: "flex",
        flexDirection: "column",
        alignItems: "center",
        justifyContent: "center",
        textAlign: "center"
      }}>
        <h2 style={{
          fontSize: "1.5rem",
          fontWeight: "600",
          color: "#dc2626",
          marginBottom: "1rem"
        }}>Become a Hero! ❤️</h2>
        <p style={{
          fontSize: "1.125rem",
          color: "#4b5563",
          maxWidth: "800px"
        }}>
          Register as a blood donor today and join our community of lifesavers. 
          Your registration takes just a minute, but your impact lasts a lifetime.
        </p>
      </div>
    </div>
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
      {/* Top Components */}
      <Header />
      <Banner />

      {/* Main Content */}
      <div style={{
        width: "100%",
        maxWidth: "800px",
        backgroundColor: "white",
        borderRadius: "8px",
        boxShadow: "0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)",
        padding: "2rem",
        marginBottom: "3rem"
      }}>
        <div style={{
          textAlign: "center",
          marginBottom: "2rem"
        }}>
          <span style={{ color: "#dc2626", fontSize: "28px" }}>❤</span>
          <h2 style={{
            fontSize: "1.875rem",
            fontWeight: "bold",
            color: "#111827",
            marginTop: "0.5rem"
          }}>
            Donor Registration
          </h2>
          <p style={{
            fontSize: "1.125rem",
            color: "#4b5563",
            marginTop: "0.5rem"
          }}>
            Create your account to start saving lives
          </p>
        </div>

        <form onSubmit={handleRegister}>
          <div style={{
            display: "grid",
            gridTemplateColumns: "1fr 1fr",
            gap: "1rem",
            marginBottom: "1.5rem"
          }}>
            <div>
              <label htmlFor="fullName" style={{
                display: "block",
                fontSize: "0.875rem",
                fontWeight: "500",
                color: "#374151",
                marginBottom: "0.5rem"
              }}>
                Full Name *
              </label>
              <input
                id="fullName"
                name="fullName"
                type="text"
                placeholder="Enter your full name"
                style={{
                  width: "100%",
                  padding: "0.75rem",
                  border: "1px solid #d1d5db",
                  borderRadius: "0.5rem",
                  fontSize: "1rem"
                }}
                value={formData.fullName}
                onChange={handleChange}
                required
              />
            </div>

            <div>
              <label htmlFor="email" style={{
                display: "block",
                fontSize: "0.875rem",
                fontWeight: "500",
                color: "#374151",
                marginBottom: "0.5rem"
              }}>
                Email Address *
              </label>
              <input
                id="email"
                name="email"
                type="email"
                placeholder="Enter your email"
                style={{
                  width: "100%",
                  padding: "0.75rem",
                  border: "1px solid #d1d5db",
                  borderRadius: "0.5rem",
                  fontSize: "1rem"
                }}
                value={formData.email}
                onChange={handleChange}
                required
              />
            </div>
          </div>

          <div style={{
            display: "grid",
            gridTemplateColumns: "1fr 1fr",
            gap: "1rem",
            marginBottom: "1.5rem"
          }}>
            <div>
              <label htmlFor="password" style={{
                display: "block",
                fontSize: "0.875rem",
                fontWeight: "500",
                color: "#374151",
                marginBottom: "0.5rem"
              }}>
                Password *
              </label>
              <input
                id="password"
                name="password"
                type="password"
                placeholder="Create a strong password"
                style={{
                  width: "100%",
                  padding: "0.75rem",
                  border: "1px solid #d1d5db",
                  borderRadius: "0.5rem",
                  fontSize: "1rem"
                }}
                value={formData.password}
                onChange={handleChange}
                required
              />
            </div>

            <div>
              <label htmlFor="confirmPassword" style={{
                display: "block",
                fontSize: "0.875rem",
                fontWeight: "500",
                color: "#374151",
                marginBottom: "0.5rem"
              }}>
                Confirm Password *
              </label>
              <input
                id="confirmPassword"
                name="confirmPassword"
                type="password"
                placeholder="Confirm your password"
                style={{
                  width: "100%",
                  padding: "0.75rem",
                  border: "1px solid #d1d5db",
                  borderRadius: "0.5rem",
                  fontSize: "1rem"
                }}
                value={formData.confirmPassword}
                onChange={handleChange}
                required
              />
            </div>
          </div>

          <div style={{
            display: "grid",
            gridTemplateColumns: "1fr 1fr",
            gap: "1rem",
            marginBottom: "1.5rem"
          }}>
            <div>
              <label htmlFor="phone" style={{
                display: "block",
                fontSize: "0.875rem",
                fontWeight: "500",
                color: "#374151",
                marginBottom: "0.5rem"
              }}>
                Phone Number *
              </label>
              <input
                id="phone"
                name="phone"
                type="tel"
                placeholder="Enter your phone number"
                style={{
                  width: "100%",
                  padding: "0.75rem",
                  border: "1px solid #d1d5db",
                  borderRadius: "0.5rem",
                  fontSize: "1rem"
                }}
                value={formData.phone}
                onChange={handleChange}
                required
              />
            </div>

            <div>
              <label htmlFor="dateOfBirth" style={{
                display: "block",
                fontSize: "0.875rem",
                fontWeight: "500",
                color: "#374151",
                marginBottom: "0.5rem"
              }}>
                Date of Birth *
              </label>
              <input
                id="dateOfBirth"
                name="dateOfBirth"
                type="date"
                style={{
                  width: "100%",
                  padding: "0.75rem",
                  border: "1px solid #d1d5db",
                  borderRadius: "0.5rem",
                  fontSize: "1rem"
                }}
                value={formData.dateOfBirth}
                onChange={handleChange}
                required
              />
            </div>
          </div>

          <div style={{
            marginBottom: "1.5rem"
          }}>
            <label htmlFor="bloodType" style={{
              display: "block",
              fontSize: "0.875rem",
              fontWeight: "500",
              color: "#374151",
              marginBottom: "0.5rem"
            }}>
              Blood Type *
            </label>
            <select
              id="bloodType"
              name="bloodType"
              style={{
                width: "100%",
                padding: "0.75rem",
                border: "1px solid #d1d5db",
                borderRadius: "0.5rem",
                fontSize: "1rem",
                backgroundColor: "white"
              }}
              value={formData.bloodType}
              onChange={handleChange}
              required
            >
              <option value="">Select your blood type</option>
              <option value="A+">A+</option>
              <option value="A-">A-</option>
              <option value="B+">B+</option>
              <option value="B-">B-</option>
              <option value="AB+">AB+</option>
              <option value="AB-">AB-</option>
              <option value="O+">O+</option>
              <option value="O-">O-</option>
              <option value="Unknown">I don't know</option>
            </select>
          </div>

          <div style={{
            marginBottom: "1.5rem"
          }}>
            <label htmlFor="address" style={{
              display: "block",
              fontSize: "0.875rem",
              fontWeight: "500",
              color: "#374151",
              marginBottom: "0.5rem"
            }}>
              Address
            </label>
            <input
              id="address"
              name="address"
              type="text"
              placeholder="Enter your street address"
              style={{
                width: "100%",
                padding: "0.75rem",
                border: "1px solid #d1d5db",
                borderRadius: "0.5rem",
                fontSize: "1rem"
              }}
              value={formData.address}
              onChange={handleChange}
            />
          </div>

          <div style={{
            display: "grid",
            gridTemplateColumns: "2fr 2fr 1fr",
            gap: "1rem",
            marginBottom: "2rem"
          }}>
            <div>
              <label htmlFor="city" style={{
                display: "block",
                fontSize: "0.875rem",
                fontWeight: "500",
                color: "#374151",
                marginBottom: "0.5rem"
              }}>
                City
              </label>
              <input
                id="city"
                name="city"
                type="text"
                placeholder="City"
                style={{
                  width: "100%",
                  padding: "0.75rem",
                  border: "1px solid #d1d5db",
                  borderRadius: "0.5rem",
                  fontSize: "1rem"
                }}
                value={formData.city}
                onChange={handleChange}
              />
            </div>

            <div>
              <label htmlFor="state" style={{
                display: "block",
                fontSize: "0.875rem",
                fontWeight: "500",
                color: "#374151",
                marginBottom: "0.5rem"
              }}>
                State
              </label>
              <input
                id="state"
                name="state"
                type="text"
                placeholder="State"
                style={{
                  width: "100%",
                  padding: "0.75rem",
                  border: "1px solid #d1d5db",
                  borderRadius: "0.5rem",
                  fontSize: "1rem"
                }}
                value={formData.state}
                onChange={handleChange}
              />
            </div>

            <div>
              <label htmlFor="zipCode" style={{
                display: "block",
                fontSize: "0.875rem",
                fontWeight: "500",
                color: "#374151",
                marginBottom: "0.5rem"
              }}>
                Zip Code
              </label>
              <input
                id="zipCode"
                name="zipCode"
                type="text"
                placeholder="Zip"
                style={{
                  width: "100%",
                  padding: "0.75rem",
                  border: "1px solid #d1d5db",
                  borderRadius: "0.5rem",
                  fontSize: "1rem"
                }}
                value={formData.zipCode}
                onChange={handleChange}
              />
            </div>
          </div>

          <div style={{
            display: "flex",
            alignItems: "center",
            marginBottom: "2rem"
          }}>
            <input
              type="checkbox"
              id="terms"
              style={{
                marginRight: "0.75rem",
                width: "1.25rem",
                height: "1.25rem"
              }}
              required
            />
            <label htmlFor="terms" style={{
              fontSize: "0.875rem",
              color: "#4b5563"
            }}>
              I agree to the <a href="#" style={{ color: "#dc2626" }}>Terms of Service</a> and <a href="#" style={{ color: "#dc2626" }}>Privacy Policy</a>
            </label>
          </div>

          <button
            type="submit"
            style={{
              width: "100%",
              backgroundColor: "#ef4444",
              color: "black",
              padding: "0.75rem 0",
              borderRadius: "0.75rem",
              fontSize: "1.125rem",
              fontWeight: "600",
              boxShadow: "0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)",
              border: "none",
              cursor: "pointer",
              transition: "transform 0.3s ease"
            }}
            onMouseOver={(e) => e.currentTarget.style.transform = "scale(1.05)"}
            onMouseOut={(e) => e.currentTarget.style.transform = "scale(1)"}
          >
            Register as Donor
          </button>

          <p style={{
            marginTop: "1.5rem",
            textAlign: "center",
            fontSize: "0.875rem",
            color: "#6b7280"
          }}>
            Already have an account?{" "}
            <a href="/donor-login" style={{ color: "#dc2626", fontWeight: "500" }}>
              Login here
            </a>
          </p>
        </form>
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

export default DonorRegister;