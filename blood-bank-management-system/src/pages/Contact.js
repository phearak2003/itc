import React, { useState } from "react";
import { useNavigate } from "react-router-dom";

const Contact = () => {
  const navigate = useNavigate();
  
  // State to handle form inputs
  const [formData, setFormData] = useState({
    firstName: "",
    lastName: "",
    email: "",
    phone: "",
    message: "",
  });
  
  // Form status states
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [submitStatus, setSubmitStatus] = useState(null); // null, 'success', 'error'
  const [errors, setErrors] = useState({});

  // Handle form field changes
  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData((prev) => ({
      ...prev,
      [name]: value,
    }));
    
    // Clear field-specific error when typing
    if (errors[name]) {
      setErrors(prev => ({
        ...prev,
        [name]: ""
      }));
    }
  };

  // Validate form
  const validateForm = () => {
    const newErrors = {};
    
    // First name validation
    if (!formData.firstName.trim()) {
      newErrors.firstName = "First name is required";
    }
    
    // Last name validation
    if (!formData.lastName.trim()) {
      newErrors.lastName = "Last name is required";
    }
    
    // Email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!formData.email.trim()) {
      newErrors.email = "Email is required";
    } else if (!emailRegex.test(formData.email)) {
      newErrors.email = "Please enter a valid email";
    }
    
    // Phone validation (optional but validate format if provided)
    if (formData.phone) {
      const phoneRegex = /^\+?[0-9\s\-()]{7,20}$/;
      if (!phoneRegex.test(formData.phone)) {
        newErrors.phone = "Please enter a valid phone number";
      }
    }
    
    // Message validation
    if (!formData.message.trim()) {
      newErrors.message = "Message is required";
    } else if (formData.message.length < 10) {
      newErrors.message = "Message must be at least 10 characters";
    }
    
    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  // Handle form submission
  const handleSubmit = async (e) => {
    e.preventDefault();
    
    if (!validateForm()) {
      return;
    }
    
    setIsSubmitting(true);
    setSubmitStatus(null);
    
    try {
      // Simulating API call with timeout
      await new Promise(resolve => setTimeout(resolve, 1500));
      
      // For demonstration purposes only - replace with actual API call
      console.log("Form submitted:", formData);
      
      // Show success message
      setSubmitStatus("success");
      
      // Reset form after successful submission
      setTimeout(() => {
        setFormData({
          firstName: "",
          lastName: "",
          email: "",
          phone: "",
          message: "",
        });
        setSubmitStatus(null);
      }, 3000);
      
    } catch (error) {
      console.error("Submission error:", error);
      setSubmitStatus("error");
    } finally {
      setIsSubmitting(false);
    }
  };

  // Reset the form and status
  const handleReset = () => {
    setFormData({
      firstName: "",
      lastName: "",
      email: "",
      phone: "",
      message: "",
    });
    setErrors({});
    setSubmitStatus(null);
  };
  
  // Simple Header component to match the Home page
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
          <a href="/contact" style={{ 
            margin: "0 10px", 
            color: "#dc2626", 
            fontWeight: "500", 
            textDecoration: "none",
            borderBottom: "2px solid #dc2626",
            paddingBottom: "5px"
          }}>Contact</a>
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
      padding: "0"
    }}>
      <Header />
      
      {/* Page Banner */}
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
          }}>Get in Touch With Us 📞</h2>
          <p style={{
            fontSize: "1.125rem",
            color: "#4b5563",
            maxWidth: "800px"
          }}>
            Have questions about blood donation? Want to organize a blood drive? We'd love to hear from you!
          </p>
        </div>
      </div>
      
      {/* Contact Form Section */}
      <div style={{
        width: "100%",
        maxWidth: "800px",
        backgroundColor: "white",
        borderRadius: "8px",
        boxShadow: "0 4px 6px rgba(0,0,0,0.1)",
        margin: "2rem auto",
        padding: "2rem",
      }}>
        <h1 style={{
          fontSize: "2rem",
          fontWeight: "700",
          color: "#dc2626",
          textAlign: "center",
          marginBottom: "1.5rem"
        }}>
          Contact Us
        </h1>
        <p style={{
          fontSize: "1.125rem",
          color: "#4b5563",
          textAlign: "center",
          marginBottom: "2rem"
        }}>
          We'll get back to you within 24 hours
        </p>

        {submitStatus === "success" ? (
          <div style={{
            backgroundColor: "#f0fdf4",
            border: "1px solid #86efac",
            borderRadius: "8px",
            padding: "2rem",
            textAlign: "center",
            marginBottom: "1rem"
          }}>
            <div style={{ fontSize: "3rem", marginBottom: "1rem" }}>✅</div>
            <h3 style={{
              fontSize: "1.5rem",
              fontWeight: "600",
              color: "#15803d",
              marginBottom: "1rem"
            }}>Thank you!</h3>
            <p style={{
              fontSize: "1.125rem",
              color: "#166534",
              marginBottom: "1.5rem"
            }}>
              Your message has been sent successfully. We'll be in touch soon.
            </p>
            <button
              onClick={handleReset}
              style={{
                backgroundColor: "#dc2626",
                color: "white",
                padding: "0.75rem 1.5rem",
                borderRadius: "0.5rem",
                border: "none",
                fontSize: "1rem",
                fontWeight: "500",
                cursor: "pointer",
                transition: "background-color 0.3s ease"
              }}
              onMouseOver={(e) => e.currentTarget.style.backgroundColor = "#b91c1c"}
              onMouseOut={(e) => e.currentTarget.style.backgroundColor = "#dc2626"}
            >
              Send another message
            </button>
          </div>
        ) : submitStatus === "error" ? (
          <div style={{
            backgroundColor: "#fef2f2",
            border: "1px solid #fecaca",
            borderRadius: "8px",
            padding: "2rem",
            textAlign: "center",
            marginBottom: "1rem"
          }}>
            <div style={{ fontSize: "3rem", marginBottom: "1rem" }}>❌</div>
            <h3 style={{
              fontSize: "1.5rem",
              fontWeight: "600",
              color: "#b91c1c",
              marginBottom: "1rem"
            }}>Something went wrong</h3>
            <p style={{
              fontSize: "1.125rem",
              color: "#991b1b",
              marginBottom: "1.5rem"
            }}>
              There was an error sending your message. Please try again.
            </p>
            <button
              onClick={() => setSubmitStatus(null)}
              style={{
                backgroundColor: "#dc2626",
                color: "white",
                padding: "0.75rem 1.5rem",
                borderRadius: "0.5rem",
                border: "none",
                fontSize: "1rem",
                fontWeight: "500",
                cursor: "pointer",
                transition: "background-color 0.3s ease"
              }}
              onMouseOver={(e) => e.currentTarget.style.backgroundColor = "#b91c1c"}
              onMouseOut={(e) => e.currentTarget.style.backgroundColor = "#dc2626"}
            >
              Try again
            </button>
          </div>
        ) : (
          <form onSubmit={handleSubmit}>
            <div style={{
              display: "grid",
              gridTemplateColumns: "1fr 1fr",
              gap: "1rem",
              marginBottom: "1rem"
            }}>
              <div>
                <label style={{
                  display: "block",
                  marginBottom: "0.5rem",
                  fontSize: "0.875rem",
                  fontWeight: "500",
                  color: "#4b5563"
                }}>
                  First name
                </label>
                <input
                  type="text"
                  name="firstName"
                  value={formData.firstName}
                  onChange={handleChange}
                  style={{
                    width: "100%",
                    padding: "0.75rem",
                    borderRadius: "0.375rem",
                    border: errors.firstName ? "1px solid #f87171" : "1px solid #d1d5db",
                    boxShadow: "0 1px 2px rgba(0,0,0,0.05)",
                    outline: "none",
                    transition: "border-color 0.3s ease"
                  }}
                />
                {errors.firstName && (
                  <p style={{ color: "#ef4444", fontSize: "0.875rem", marginTop: "0.25rem" }}>
                    {errors.firstName}
                  </p>
                )}
              </div>

              <div>
                <label style={{
                  display: "block",
                  marginBottom: "0.5rem",
                  fontSize: "0.875rem",
                  fontWeight: "500",
                  color: "#4b5563"
                }}>
                  Last name
                </label>
                <input
                  type="text"
                  name="lastName"
                  value={formData.lastName}
                  onChange={handleChange}
                  style={{
                    width: "100%",
                    padding: "0.75rem",
                    borderRadius: "0.375rem",
                    border: errors.lastName ? "1px solid #f87171" : "1px solid #d1d5db",
                    boxShadow: "0 1px 2px rgba(0,0,0,0.05)",
                    outline: "none",
                    transition: "border-color 0.3s ease"
                  }}
                />
                {errors.lastName && (
                  <p style={{ color: "#ef4444", fontSize: "0.875rem", marginTop: "0.25rem" }}>
                    {errors.lastName}
                  </p>
                )}
              </div>
            </div>

            <div style={{ marginBottom: "1rem" }}>
              <label style={{
                display: "block",
                marginBottom: "0.5rem",
                fontSize: "0.875rem",
                fontWeight: "500",
                color: "#4b5563"
              }}>
                Email
              </label>
              <input
                type="email"
                name="email"
                value={formData.email}
                onChange={handleChange}
                placeholder="yourname@email.com"
                style={{
                  width: "100%",
                  padding: "0.75rem",
                  borderRadius: "0.375rem",
                  border: errors.email ? "1px solid #f87171" : "1px solid #d1d5db",
                  boxShadow: "0 1px 2px rgba(0,0,0,0.05)",
                  outline: "none",
                  transition: "border-color 0.3s ease"
                }}
              />
              {errors.email && (
                <p style={{ color: "#ef4444", fontSize: "0.875rem", marginTop: "0.25rem" }}>
                  {errors.email}
                </p>
              )}
            </div>

            <div style={{ marginBottom: "1rem" }}>
              <label style={{
                display: "block",
                marginBottom: "0.5rem",
                fontSize: "0.875rem",
                fontWeight: "500",
                color: "#4b5563"
              }}>
                Phone (optional)
              </label>
              <input
                type="tel"
                name="phone"
                value={formData.phone}
                onChange={handleChange}
                placeholder="+855 97-123-345"
                style={{
                  width: "100%",
                  padding: "0.75rem",
                  borderRadius: "0.375rem",
                  border: errors.phone ? "1px solid #f87171" : "1px solid #d1d5db",
                  boxShadow: "0 1px 2px rgba(0,0,0,0.05)",
                  outline: "none",
                  transition: "border-color 0.3s ease"
                }}
              />
              {errors.phone && (
                <p style={{ color: "#ef4444", fontSize: "0.875rem", marginTop: "0.25rem" }}>
                  {errors.phone}
                </p>
              )}
            </div>

            <div style={{ marginBottom: "1.5rem" }}>
              <label style={{
                display: "block",
                marginBottom: "0.5rem",
                fontSize: "0.875rem",
                fontWeight: "500",
                color: "#4b5563"
              }}>
                Message
              </label>
              <textarea
                name="message"
                value={formData.message}
                onChange={handleChange}
                placeholder="Type your message here..."
                rows={5}
                style={{
                  width: "100%",
                  padding: "0.75rem",
                  borderRadius: "0.375rem",
                  border: errors.message ? "1px solid #f87171" : "1px solid #d1d5db",
                  boxShadow: "0 1px 2px rgba(0,0,0,0.05)",
                  outline: "none",
                  transition: "border-color 0.3s ease",
                  resize: "vertical"
                }}
              />
              {errors.message && (
                <p style={{ color: "#ef4444", fontSize: "0.875rem", marginTop: "0.25rem" }}>
                  {errors.message}
                </p>
              )}
            </div>

            <div style={{
              display: "flex",
              justifyContent: "flex-end",
              gap: "1rem"
            }}>
              <button
                type="button"
                onClick={handleReset}
                style={{
                  backgroundColor: "#f3f4f6",
                  color: "#4b5563",
                  padding: "0.75rem 1.5rem",
                  borderRadius: "0.5rem",
                  border: "1px solid #d1d5db",
                  fontSize: "1rem",
                  fontWeight: "500",
                  cursor: "pointer",
                  transition: "background-color 0.3s ease"
                }}
                onMouseOver={(e) => e.currentTarget.style.backgroundColor = "#e5e7eb"}
                onMouseOut={(e) => e.currentTarget.style.backgroundColor = "#f3f4f6"}
              >
                Reset
              </button>
              <button
                type="submit"
                disabled={isSubmitting}
                style={{
                  backgroundColor: "#dc2626",
                  color: "white",
                  padding: "0.75rem 1.5rem",
                  borderRadius: "0.5rem",
                  border: "none",
                  fontSize: "1rem",
                  fontWeight: "500",
                  cursor: isSubmitting ? "not-allowed" : "pointer",
                  opacity: isSubmitting ? "0.7" : "1",
                  transition: "background-color 0.3s ease",
                  display: "flex",
                  alignItems: "center",
                  justifyContent: "center"
                }}
                onMouseOver={(e) => !isSubmitting && (e.currentTarget.style.backgroundColor = "#b91c1c")}
                onMouseOut={(e) => !isSubmitting && (e.currentTarget.style.backgroundColor = "#dc2626")}
              >
                {isSubmitting ? (
                  <>
                    <div style={{
                      width: "1rem",
                      height: "1rem",
                      border: "2px solid white",
                      borderTopColor: "transparent",
                      borderRadius: "50%",
                      animation: "spin 1s linear infinite",
                      marginRight: "0.5rem",
                      display: "inline-block"
                    }} />
                    Processing...
                  </>
                ) : (
                  <>Send Message</>
                )}
              </button>
            </div>
          </form>
        )}
      </div>
      
      {/* Contact information cards */}
      <div style={{
        width: "100%",
        maxWidth: "1200px",
        margin: "2rem auto",
        display: "grid",
        gridTemplateColumns: "repeat(auto-fit, minmax(250px, 1fr))",
        gap: "1.5rem",
        padding: "0 1.5rem"
      }}>
        <div style={{
          backgroundColor: "white",
          borderRadius: "0.5rem",
          padding: "1.5rem",
          boxShadow: "0 4px 6px rgba(0,0,0,0.1)"
        }}>
          <div style={{
            fontSize: "2rem",
            marginBottom: "1rem",
            textAlign: "center"
          }}>📍</div>
          <h3 style={{
            fontSize: "1.25rem",
            fontWeight: "600",
            marginBottom: "0.75rem",
            textAlign: "center"
          }}>Our Location</h3>
          <p style={{
            color: "#4b5563",
            textAlign: "center"
          }}>
            123 Blood Drive Avenue<br />
            Medical District<br />
            Health City, HC 12345
          </p>
        </div>
        
        <div style={{
          backgroundColor: "white",
          borderRadius: "0.5rem",
          padding: "1.5rem",
          boxShadow: "0 4px 6px rgba(0,0,0,0.1)"
        }}>
          <div style={{
            fontSize: "2rem",
            marginBottom: "1rem",
            textAlign: "center"
          }}>📞</div>
          <h3 style={{
            fontSize: "1.25rem",
            fontWeight: "600",
            marginBottom: "0.75rem",
            textAlign: "center"
          }}>Call Us</h3>
          <p style={{
            color: "#4b5563",
            textAlign: "center"
          }}>
            Main Office: +1 (555) 123-4567<br />
            Support: +1 (555) 765-4321<br />
            Emergency: +1 (555) 911-0000
          </p>
        </div>
        
        <div style={{
          backgroundColor: "white",
          borderRadius: "0.5rem",
          padding: "1.5rem",
          boxShadow: "0 4px 6px rgba(0,0,0,0.1)"
        }}>
          <div style={{
            fontSize: "2rem",
            marginBottom: "1rem",
            textAlign: "center"
          }}>📧</div>
          <h3 style={{
            fontSize: "1.25rem",
            fontWeight: "600",
            marginBottom: "0.75rem",
            textAlign: "center"
          }}>Email Us</h3>
          <p style={{
            color: "#4b5563",
            textAlign: "center"
          }}>
            General: info@blooddonate.org<br />
            Support: help@blooddonate.org<br />
            Donations: donate@blooddonate.org
          </p>
        </div>
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
      
      <style>
        {`
          @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
          }
        `}
      </style>
    </div>
  );
};

export default Contact;