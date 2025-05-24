import React, { useState } from "react";

const DonorSurvey = () => {
  const [formData, setFormData] = useState({
    fullName: "",
    gender: "",
    dateOfBirth: "",
    age: "",
    phoneNumber: "",
    email: "",
    nationalID: "",
    bloodType: "",
    weight: "",
    previousDonation: "",
    lastDonationDate: "",
    tattoosOrPiercings: "",
    feelingWell: "",
    recentIllness: "",
    medication: "",
    pregnancy: "",
    chronicDiseases: "",
    eligible: true, // Eligibility flag
    province: "",
    donationCenter: "",
    contactLanguage: ""
  });
  
  const [showThankYou, setShowThankYou] = useState(false);

  // Function to calculate age based on DOB
  const calculateAge = (dob) => {
    const birthYear = new Date(dob).getFullYear();
    const currentYear = new Date().getFullYear();
    return currentYear - birthYear;
  };

  // Handle input changes
  const handleChange = (e) => {
    const { name, value } = e.target;
    let updatedFormData = { ...formData, [name]: value };

    if (name === "dateOfBirth") {
      const age = calculateAge(value);
      updatedFormData.age = age;
      if (age < 18) updatedFormData.eligible = false;
    }

    if (name === "weight" && value !== "") {
      if (parseFloat(value) <= 45) updatedFormData.eligible = false;
    }

    setFormData(updatedFormData);
  };

  // Handle form submission
  const handleSubmit = (e) => {
    e.preventDefault();

    // Check eligibility before proceeding
    if (formData.age < 18 || formData.weight <= 45) {
      alert("You do not meet the requirements to donate blood.");
      return;
    }

    console.log("Survey Submitted:", formData);
    setShowThankYou(true); // Show thank you page
  };

  // Styles object
  const styles = {
    container: {
      display: 'flex',
      flexDirection: 'column',
      minHeight: '100vh',
      backgroundColor: '#f3f4f6'
    },
    header: {
      backgroundColor: '#ffffff',
      boxShadow: '0 4px 6px -1px rgba(0, 0, 0, 0.1)',
      padding: '16px',
      width: '100%'
    },
    headerContent: {
      display: 'flex',
      justifyContent: 'space-between',
      alignItems: 'center',
      maxWidth: '1152px',
      margin: '0 auto'
    },
    logo: {
      display: 'flex',
      alignItems: 'center'
    },
    heartIcon: {
      color: '#dc2626',
      fontSize: '24px'
    },
    logoText: {
      marginLeft: '8px',
      fontWeight: 'bold',
      fontSize: '18px'
    },
    nav: {},
    navLink: {
      margin: '0 8px',
      color: '#4b5563',
      textDecoration: 'none'
    },
    activeNavLink: {
      margin: '0 8px',
      color: '#dc2626',
      fontWeight: '500',
      borderBottom: '2px solid #dc2626',
      paddingBottom: '4px',
      textDecoration: 'none'
    },
    mainContent: {
      flexGrow: 1,
      padding: '32px 16px'
    },
    formContainer: {
      width: '100%',
      maxWidth: '768px',
      margin: '0 auto',
      backgroundColor: '#ffffff',
      borderRadius: '8px',
      boxShadow: '0 10px 15px -3px rgba(0, 0, 0, 0.1)',
      padding: '32px'
    },
    formTitle: {
      fontSize: '24px',
      fontWeight: 'bold',
      color: '#dc2626',
      marginBottom: '16px',
      textAlign: 'center'
    },
    formDescription: {
      color: '#4b5563',
      marginBottom: '24px',
      textAlign: 'center'
    },
    errorAlert: {
      backgroundColor: '#fee2e2',
      border: '1px solid #ef4444',
      color: '#b91c1c',
      padding: '12px 16px',
      borderRadius: '4px',
      marginBottom: '24px'
    },
    sectionContainer: {
      padding: '16px',
      borderRadius: '6px',
      marginBottom: '24px'
    },
    redSection: {
      backgroundColor: '#fef2f2'
    },
    blueSection: {
      backgroundColor: '#eff6ff'
    },
    greenSection: {
      backgroundColor: '#ecfdf5'
    },
    yellowSection: {
      backgroundColor: '#fffbeb'
    },
    sectionTitle: {
      fontSize: '18px',
      fontWeight: '600',
      marginBottom: '16px'
    },
    redSectionTitle: {
      color: '#b91c1c'
    },
    blueSectionTitle: {
      color: '#1d4ed8'
    },
    greenSectionTitle: {
      color: '#047857'
    },
    yellowSectionTitle: {
      color: '#b45309'
    },
    formGrid: {
      display: 'grid',
      gridTemplateColumns: '1fr',
      gap: '16px'
    },
    formGroup: {
      marginBottom: '16px'
    },
    label: {
      display: 'block',
      marginBottom: '4px',
      fontWeight: '500'
    },
    input: {
      width: '100%',
      padding: '8px',
      border: '1px solid #d1d5db',
      borderRadius: '4px'
    },
    select: {
      width: '100%',
      padding: '8px',
      border: '1px solid #d1d5db',
      borderRadius: '4px'
    },
    helpText: {
      marginTop: '4px',
      fontSize: '14px',
      color: '#6b7280'
    },
    disclaimerText: {
      fontSize: '14px',
      color: '#6b7280',
      marginBottom: '16px',
      textAlign: 'center'
    },
    submitButton: {
      backgroundColor: '#dc2626',
      color: '#ffffff',
      fontWeight: 'bold',
      padding: '12px 32px',
      borderRadius: '8px',
      fontSize: '18px',
      border: 'none',
      cursor: 'pointer'
    },
    submitButtonHover: {
      backgroundColor: '#b91c1c'
    },
    footer: {
      backgroundColor: '#1f2937',
      color: '#ffffff',
      padding: '32px',
      width: '100%'
    },
    footerContent: {
      maxWidth: '1152px',
      margin: '0 auto',
      display: 'flex',
      flexDirection: 'column',
      alignItems: 'center'
    },
    footerLogo: {
      display: 'flex',
      alignItems: 'center',
      marginBottom: '24px'
    },
    footerHeartIcon: {
      color: '#ef4444',
      fontSize: '24px'
    },
    footerInfo: {
      marginBottom: '24px',
      textAlign: 'center'
    },
    footerLinks: {
      display: 'flex',
      gap: '16px'
    },
    footerLink: {
      color: '#ffffff',
      textDecoration: 'none'
    },
    footerLinkHover: {
      color: '#fca5a5'
    },
    thankYouContainer: {
      display: 'flex',
      flexGrow: 1,
      alignItems: 'center',
      justifyContent: 'center',
      padding: '32px 16px'
    },
    thankYouCard: {
      width: '100%',
      maxWidth: '768px',
      backgroundColor: '#ffffff',
      borderRadius: '8px',
      boxShadow: '0 10px 15px -3px rgba(0, 0, 0, 0.1)',
      padding: '32px',
      textAlign: 'center'
    },
    checkIcon: {
      color: '#10b981',
      fontSize: '64px',
      marginBottom: '24px'
    },
    thankYouTitle: {
      fontSize: '30px',
      fontWeight: 'bold',
      color: '#1f2937',
      marginBottom: '16px'
    },
    thankYouMessage: {
      fontSize: '20px',
      color: '#4b5563',
      marginBottom: '24px'
    },
    thankYouSubMessage: {
      fontSize: '18px',
      color: '#4b5563',
      marginBottom: '32px'
    },
    nextStepsContainer: {
      backgroundColor: '#fef2f2',
      padding: '24px',
      borderRadius: '8px',
      marginBottom: '32px'
    },
    nextStepsTitle: {
      fontSize: '20px',
      fontWeight: '600',
      color: '#dc2626',
      marginBottom: '16px'
    },
    nextStepsList: {
      textAlign: 'left',
      marginBottom: '16px'
    },
    nextStepsItem: {
      display: 'flex',
      alignItems: 'start',
      marginBottom: '12px'
    },
    nextStepsNumber: {
      color: '#ef4444',
      marginRight: '8px'
    },
    returnButton: {
      backgroundColor: '#dc2626',
      color: '#ffffff',
      fontWeight: 'bold',
      padding: '12px 32px',
      borderRadius: '8px',
      fontSize: '18px',
      border: 'none',
      cursor: 'pointer'
    },
    // Media query styles can be applied conditionally in the component
    mediaMd: {
      formGrid: {
        gridTemplateColumns: '1fr 1fr'
      }
    }
  };

  // Simple Header component matching the home page
  const Header = () => (
    <div style={styles.header}>
      <div style={styles.headerContent}>
        <div style={styles.logo}>
          <span style={styles.heartIcon}>❤</span>
          <span style={styles.logoText}>BloodDonate</span>
        </div>
        <nav style={styles.nav}>
          <a href="/" style={styles.activeNavLink}>Home</a>
          <a href="/about" style={styles.navLink}>About</a>
          <a href="/ourteam" style={styles.navLink}>OurTeam</a>
          <a href="/contact" style={styles.navLink}>Contact</a>
        </nav>
      </div>
    </div>
  );
  
  // Thank You Page
  const ThankYouPage = () => (
    <div style={styles.container}>
      <Header />
      
      <div style={styles.thankYouContainer}>
        <div style={styles.thankYouCard}>
          <div style={styles.checkIcon}>✓</div>
          <h2 style={styles.thankYouTitle}>Thank You!</h2>
          <p style={styles.thankYouMessage}>
            Your donation survey has been successfully submitted.
          </p>
          <p style={styles.thankYouSubMessage}>
            We appreciate your willingness to donate blood and help save lives in Cambodia.
            Our team will review your information and contact you soon with next steps.
          </p>
          
          <div style={styles.nextStepsContainer}>
            <h3 style={styles.nextStepsTitle}>What's Next?</h3>
            <ul style={styles.nextStepsList}>
              <li style={styles.nextStepsItem}>
                <span style={styles.nextStepsNumber}>1.</span>
                <span>You'll receive a confirmation call or SMS to schedule your donation appointment</span>
              </li>
              <li style={styles.nextStepsItem}>
                <span style={styles.nextStepsNumber}>2.</span>
                <span>On your appointment day, bring your ID card and arrive well-rested and hydrated</span>
              </li>
              <li style={styles.nextStepsItem}>
                <span style={styles.nextStepsNumber}>3.</span>
                <span>A brief medical check will be performed before donation to ensure it's safe for you to donate</span>
              </li>
            </ul>
          </div>
          
          <button 
            onClick={() => setShowThankYou(false)} 
            style={styles.returnButton}
          >
            Return to Survey
          </button>
        </div>
      </div>
      
      <footer style={styles.footer}>
        <div style={styles.footerContent}>
          <div style={styles.footerLogo}>
            <span style={styles.footerHeartIcon}>❤</span>
            <span style={styles.logoText}>BloodDonate</span>
          </div>
          
          <div style={styles.footerInfo}>
            <p>Connecting donors with those in need since 2023</p>
            <p style={{marginTop: '8px'}}>© 2025 BloodDonate. All rights reserved.</p>
          </div>
          
          <div style={styles.footerLinks}>
            <a href="#" style={styles.footerLink}>Privacy</a>
            <a href="#" style={styles.footerLink}>Terms</a>
            <a href="#" style={styles.footerLink}>Contact</a>
          </div>
        </div>
      </footer>
    </div>
  );

  // Apply media query styles conditionally
  const getResponsiveStyles = (baseStyle) => {
    // Check if window width is greater than medium breakpoint (768px)
    const isMediumScreen = window.innerWidth >= 768;
    
    if (isMediumScreen && styles.mediaMd[baseStyle]) {
      return { ...styles[baseStyle], ...styles.mediaMd[baseStyle] };
    }
    
    return styles[baseStyle];
  };

  // Render Thank You page if submission is done
  if (showThankYou) {
    return <ThankYouPage />;
  }

  return (
    <div style={styles.container}>
      <Header />
      
      <div style={styles.mainContent}>
        <div style={styles.formContainer}>
          <h2 style={styles.formTitle}>
            Blood Donor Pre-Screening Survey
          </h2>
          <p style={styles.formDescription}>
            Thank you for your interest in donating blood in Cambodia. Please complete this survey to help us determine your eligibility.
          </p>
          
          {!formData.eligible && (
            <div style={styles.errorAlert}>
              <p>You are not eligible to donate blood at this time.</p>
            </div>
          )}
          
          <form onSubmit={handleSubmit}>
            {/* Personal Information Section */}
            <div style={{...styles.sectionContainer, ...styles.redSection}}>
              <h3 style={{...styles.sectionTitle, ...styles.redSectionTitle}}>Personal Information</h3>
              
              <div style={getResponsiveStyles('formGrid')}>
                <div>
                  <label style={styles.label}>Full Name:</label>
                  <input 
                    type="text" 
                    name="fullName" 
                    required 
                    onChange={handleChange} 
                    style={styles.input}
                  />
                </div>
                
                <div>
                  <label style={styles.label}>Gender:</label>
                  <select 
                    name="gender" 
                    required 
                    onChange={handleChange} 
                    style={styles.select}
                  >
                    <option value="">Select</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                  </select>
                </div>
                
                <div>
                  <label style={styles.label}>Date of Birth:</label>
                  <input 
                    type="date" 
                    name="dateOfBirth" 
                    required 
                    onChange={handleChange} 
                    style={styles.input}
                  />
                  {formData.age && <p style={styles.helpText}>Age: {formData.age} years</p>}
                </div>
                
                <div>
                  <label style={styles.label}>Phone Number:</label>
                  <input 
                    type="tel" 
                    name="phoneNumber"
                    placeholder="e.g., 010 123 456" 
                    required 
                    onChange={handleChange} 
                    style={styles.input}
                  />
                </div>

                <div>
                  <label style={styles.label}>Email Address (Optional):</label>
                  <input 
                    type="email" 
                    name="email" 
                    onChange={handleChange} 
                    style={styles.input}
                  />
                </div>
                
                <div>
                  <label style={styles.label}>National ID / Passport:</label>
                  <input 
                    type="text" 
                    name="nationalID" 
                    required 
                    onChange={handleChange} 
                    style={styles.input}
                  />
                </div>
              </div>
            </div>

            {/* Health Information Section */}
            <div style={{...styles.sectionContainer, ...styles.blueSection}}>
              <h3 style={{...styles.sectionTitle, ...styles.blueSectionTitle}}>Health Information</h3>
              
              <div style={getResponsiveStyles('formGrid')}>
                <div>
                  <label style={styles.label}>Blood Type (if known):</label>
                  <select 
                    name="bloodType" 
                    onChange={handleChange} 
                    style={styles.select}
                  >
                    <option value="">Select</option>
                    <option value="A+">A+</option>
                    <option value="A-">A-</option>
                    <option value="B+">B+</option>
                    <option value="B-">B-</option>
                    <option value="O+">O+</option>
                    <option value="O-">O-</option>
                    <option value="AB+">AB+</option>
                    <option value="AB-">AB-</option>
                    <option value="Unknown">I don't know</option>
                  </select>
                </div>
                
                <div>
                  <label style={styles.label}>Weight (kg):</label>
                  <input 
                    type="number" 
                    name="weight" 
                    required 
                    onChange={handleChange} 
                    style={styles.input}
                  />
                  <p style={styles.helpText}>Must be above 45kg to donate</p>
                </div>
                
                <div>
                  <label style={styles.label}>Have you donated blood before?</label>
                  <select 
                    name="previousDonation" 
                    required 
                    onChange={handleChange} 
                    style={styles.select}
                  >
                    <option value="">Select</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                  </select>
                </div>
                
                {formData.previousDonation === "Yes" && (
                  <div>
                    <label style={styles.label}>Last Donation Date:</label>
                    <input 
                      type="date" 
                      name="lastDonationDate" 
                      onChange={handleChange} 
                      style={styles.input}
                    />
                  </div>
                )}
              </div>
            </div>

            {/* Eligibility Questions Section */}
            <div style={{...styles.sectionContainer, ...styles.greenSection}}>
              <h3 style={{...styles.sectionTitle, ...styles.greenSectionTitle}}>Eligibility Questions</h3>
              
              <div style={{display: 'flex', flexDirection: 'column', gap: '16px'}}>
                <div>
                  <label style={styles.label}>Are you feeling well today?</label>
                  <select 
                    name="feelingWell" 
                    required 
                    onChange={handleChange} 
                    style={styles.select}
                  >
                    <option value="">Select</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                  </select>
                </div>
                
                <div>
                  <label style={styles.label}>Have you had dengue fever or malaria in the past 6 months?</label>
                  <select 
                    name="recentIllness" 
                    required 
                    onChange={handleChange} 
                    style={styles.select}
                  >
                    <option value="">Select</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                  </select>
                </div>
                
                <div>
                  <label style={styles.label}>Have you had any new tattoos or piercings in the last 6 months?</label>
                  <select 
                    name="tattoosOrPiercings" 
                    required 
                    onChange={handleChange} 
                    style={styles.select}
                  >
                    <option value="">Select</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                  </select>
                </div>
                
                <div>
                  <label style={styles.label}>Are you taking any medication?</label>
                  <select 
                    name="medication" 
                    required 
                    onChange={handleChange} 
                    style={styles.select}
                  >
                    <option value="">Select</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                  </select>
                </div>
                
                {formData.gender === "Female" && (
                  <div>
                    <label style={styles.label}>Are you currently pregnant or have given birth in the last 6 months?</label>
                    <select 
                      name="pregnancy" 
                      required 
                      onChange={handleChange} 
                      style={styles.select}
                    >
                      <option value="">Select</option>
                      <option value="Yes">Yes</option>
                      <option value="No">No</option>
                    </select>
                  </div>
                )}
                
                <div>
                  <label style={styles.label}>Do you have any chronic diseases (diabetes, heart disease, hepatitis, HIV, etc.)?</label>
                  <select 
                    name="chronicDiseases" 
                    required 
                    onChange={handleChange} 
                    style={styles.select}
                  >
                    <option value="">Select</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                  </select>
                </div>
              </div>
            </div>
            
            {/* Cambodia-specific information */}
            <div style={{...styles.sectionContainer, ...styles.yellowSection}}>
              <h3 style={{...styles.sectionTitle, ...styles.yellowSectionTitle}}>Additional Information</h3>
              
              <div style={{display: 'flex', flexDirection: 'column', gap: '16px'}}>
                <div>
                  <label style={styles.label}>What province/city are you from?</label>
                  <select 
                    name="province" 
                    required 
                    onChange={handleChange} 
                    style={styles.select}
                  >
                    <option value="">Select</option>
                    <option value="Phnom Penh">Phnom Penh</option>
                    <option value="Siem Reap">Siem Reap</option>
                    <option value="Battambang">Battambang</option>
                    <option value="Kampong Cham">Kampong Cham</option>
                    <option value="Kandal">Kandal</option>
                    <option value="Banteay Meanchey">Banteay Meanchey</option>
                    <option value="Kampong Thom">Kampong Thom</option>
                    <option value="Kampot">Kampot</option>
                    <option value="Kampong Speu">Kampong Speu</option>
                    <option value="Takeo">Takeo</option>
                    <option value="Svay Rieng">Svay Rieng</option>
                    <option value="Pursat">Pursat</option>
                    <option value="Preah Vihear">Preah Vihear</option>
                    <option value="Prey Veng">Prey Veng</option>
                    <option value="Kratie">Kratie</option>
                    <option value="Stung Treng">Stung Treng</option>
                    <option value="Ratanakiri">Ratanakiri</option>
                    <option value="Mondulkiri">Mondulkiri</option>
                    <option value="Koh Kong">Koh Kong</option>
                    <option value="Pailin">Pailin</option>
                    <option value="Kep">Kep</option>
                    <option value="Oddar Meanchey">Oddar Meanchey</option>
                    <option value="Preah Sihanouk">Preah Sihanouk</option>
                    <option value="Tboung Khmum">Tboung Khmum</option>
                  </select>
                </div>
                
                <div>
                  <label style={styles.label}>Preferred donation center:</label>
                  <select 
                    name="donationCenter" 
                    required 
                    onChange={handleChange} 
                    style={styles.select}
                  >
                    <option value="">Select</option>
                    <option value="National Blood Transfusion Center, Phnom Penh">National Blood Transfusion Center, Phnom Penh</option>
                    <option value="Khmer-Soviet Friendship Hospital">Khmer-Soviet Friendship Hospital</option>
                    <option value="Calmette Hospital">Calmette Hospital</option>
                    <option value="Siem Reap Provincial Hospital">Siem Reap Provincial Hospital</option>
                    <option value="Battambang Provincial Hospital">Battambang Provincial Hospital</option>
                    <option value="Mobile donation unit (will be announced)">Mobile donation unit (will be announced)</option>
                  </select>
                </div>
                
                <div>
                  <label style={styles.label}>Preferred contact language:</label>
                  <select 
                    name="contactLanguage" 
                    required 
                    onChange={handleChange} 
                    style={styles.select}
                  >
                    <option value="">Select</option>
                    <option value="Khmer">Khmer</option>
                    <option value="English">English</option>
                  </select>
                </div>
              </div>
            </div>
            
            <div style={{textAlign: 'center'}}>
              <p style={styles.disclaimerText}>
                By submitting this form, I confirm all information provided is accurate and complete.
              </p>
              
              <button 
                type="submit"
                style={styles.submitButton}
                onMouseOver={(e) => e.target.style.backgroundColor = '#b91c1c'}
                onMouseOut={(e) => e.target.style.backgroundColor = '#dc2626'}
              >
                Submit Survey
              </button>
            </div>
          </form>
        </div>
      </div>
      
      <footer style={styles.footer}>
        <div style={styles.footerContent}>
          <div style={styles.footerLogo}>
            <span style={styles.footerHeartIcon}>❤</span>
            <span style={styles.logoText}>BloodDonate</span>
          </div>
          
          <div style={styles.footerInfo}>
            <p>Connecting donors with those in need since 2023</p>
            <p style={{marginTop: '8px'}}>© 2025 BloodDonate. All rights reserved.</p>
          </div>
          
          <div style={styles.footerLinks}>
            <a 
              href="#" 
              style={styles.footerLink}
              onMouseOver={(e) => e.target.style.color = '#fca5a5'}
              onMouseOut={(e) => e.target.style.color = '#ffffff'}
            >
              Privacy
            </a>
            <a 
              href="#" 
              style={styles.footerLink}
              onMouseOver={(e) => e.target.style.color = '#fca5a5'}
              onMouseOut={(e) => e.target.style.color = '#ffffff'}
            >
              Terms
            </a>
            <a 
              href="#" 
              style={styles.footerLink}
              onMouseOver={(e) => e.target.style.color = '#fca5a5'}
              onMouseOut={(e) => e.target.style.color = '#ffffff'}
            >
              Contact
            </a>
          </div>
        </div>
      </footer>
    </div>
  );
};

export default DonorSurvey;