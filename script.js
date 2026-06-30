// Dropdown Menu Toggle
document.addEventListener("DOMContentLoaded", function () {
  const dropdownToggles = document.querySelectorAll(".dropdown-toggle");

  dropdownToggles.forEach((toggle) => {
    toggle.addEventListener("click", function (e) {
      e.preventDefault();
      const parent = this.parentElement;

      // Toggle active class
      parent.classList.toggle("active");

      // Close other dropdowns
      document.querySelectorAll(".dropdown").forEach((dropdown) => {
        if (dropdown !== parent && dropdown.classList.contains("active")) {
          dropdown.classList.remove("active");
        }
      });
    });
  });

  // Close dropdown when clicking on a submenu item
  const dropdownItems = document.querySelectorAll(".dropdown-menu li a");
  dropdownItems.forEach((item) => {
    item.addEventListener("click", function () {
      const parent = this.closest(".dropdown");
      parent.classList.remove("active");
    });
  });

  // Close dropdown when clicking outside
  document.addEventListener("click", function (e) {
    if (!e.target.closest(".dropdown")) {
      document.querySelectorAll(".dropdown.active").forEach((dropdown) => {
        dropdown.classList.remove("active");
      });
    }
  });

  // Search functionality
  const searchInput = document.querySelector("input[data-search-table]");
  if (searchInput) {
    const tableBody = searchInput.closest(".table-section").querySelector("table tbody");
    if (tableBody) {
      const rows = tableBody.querySelectorAll("tr");

      searchInput.addEventListener("keyup", function () {
        const searchTerm = this.value.toLowerCase();

        rows.forEach((row) => {
          const text = row.innerText.toLowerCase();
          if (text.includes(searchTerm)) {
            row.style.display = "";
          } else {
            row.style.display = "none";
          }
        });
      });
    }
  }
});
