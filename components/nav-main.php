<!-- Navigation Bar Component -->
    <nav class="bg-background/80 backdrop-blur-lg border-b border-white/10 sticky top-0 z-50">
      <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Main Navigation Row -->
        <div class="flex justify-between items-center h-16">
          <!-- LEFT: Logo & Desktop Links -->
          <div class="flex items-center space-x-6 lg:space-x-12">
            <!-- 1. Logo/Brand -->
            <a class="flex items-center gap-2" href="/?">
               <svg width="90" height="25" viewBox="0 0 120 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <text font-family="'Bebas Neue', sans-serif" font-size="36" fill="hsl(var(--primary))" x="0" y="28">JAPURA</text>
               </svg>
            </a>

            <!-- 2. Desktop Navigation Links (Hidden on small screens) -->
            <div class="hidden md:flex h-full space-x-8 items-center">
              <a
                href="#"
                class="text-sm font-medium text-[#f61421] relative py-5"
              >
                Home
              </a>
              <a
                href="#"
                class="text-sm text-gray-400 hover:text-white transition duration-200 py-5"
                >Calendar</a
              >
            </div>
          </div>

          <!-- RIGHT: Search, Icons, and Mobile Button -->
          <div class="flex items-center space-x-3 sm:space-x-4">
            <!-- 3. Responsive Search Bar (NOW INTERACTIVE) -->
            <div
              class="relative flex-1 max-w-[20rem] sm:max-w-none ml-4 md:ml-0 md:w-64 lg:w-80"
            >
              <input
                id="search-input"
                type="text"
                placeholder="Search"
                class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-700 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#f61421] placeholder-gray-400 text-white transition duration-300 shadow-md"
              />
              <!-- Search Icon -->
              <svg
                class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              >
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
              </svg>

              <!-- Search Results Dropdown -->
              <div
                id="search-results"
                class="absolute w-full mt-1 bg-gray-800 rounded-lg shadow-xl z-40 overflow-y-auto hidden"
              >
                <!-- Results will be injected here by JavaScript -->
              </div>
            </div>

            <!-- 4. Notification Bell Icon (Desktop Only) -->
            <button
              class="hidden md:inline-flex p-2 rounded-full hover:bg-gray-700/50 transition duration-150 ease-in-out"
            >
              <svg
                class="h-6 w-6 text-gray-400"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              >
                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
              </svg>
            </button>

            <!-- 5. Profile Avatar with Dropdown Menu (Desktop Only in Header) -->
            <div class="hidden md:block relative">
              <!-- Avatar Button -->
              <button
                id="profile-menu-button-desktop"
                class="flex-shrink-0 focus:outline-none"
              >
                <img
                  class="h-10 w-10 rounded-full object-cover ring-2 ring-[#f5141f] ring-offset-2 ring-offset-[#1A1C28]"
                  src="https://placehold.co/100x100/f5141f/ffffff?text=U"
                  onerror="this.onerror=null;this.src='https://placehold.co/100x100/334155/ffffff?text=U';"
                  alt="User Avatar"
                />
              </button>

              <!-- Dropdown Menu -->
              <div
                id="profile-menu-desktop"
                class="absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-gray-800 ring-1 ring-black ring-opacity-5 origin-top-right hidden z-30"
              >
                <a
                  href="#"
                  class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700/70 hover:text-white transition duration-150 flex items-center space-x-2"
                >
                  <svg
                    class="h-4 w-4"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  >
                    <path d="M12 20h9"></path>
                    <path
                      d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"
                    ></path>
                  </svg>
                  <span>Settings</span>
                </a>
                <div class="border-t border-gray-700/70 my-1"></div>
                <a
                  href="#"
                  class="block px-4 py-2 text-sm text-red-400 hover:bg-gray-700/70 hover:text-red-300 transition duration-150 flex items-center space-x-2"
                >
                  <svg
                    class="h-4 w-4"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  >
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                  </svg>
                  <span>Logout</span>
                </a>
              </div>
            </div>

            <!-- 6. Mobile Menu Button (Hamburger/X) -->
            <button
              id="mobile-menu-button"
              type="button"
              class="inline-flex items-center justify-center p-2 rounded-lg text-gray-400 hover:text-white hover:bg-gray-700/50 focus:outline-none md:hidden transition duration-150"
            >
              <!-- Hamburger Icon -->
              <svg
                id="hamburger-icon"
                class="block h-6 w-6"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              >
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
              </svg>
              <!-- Close X Icon (Initially hidden) -->
              <svg
                id="close-icon"
                class="hidden h-6 w-6"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              >
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Mobile Menu Dropdown (Animated) -->
      <div id="mobile-menu" class="md:hidden border-t border-gray-700/50">
        <div class="px-2 pt-3 pb-3 space-y-2 sm:px-3">
          <!-- Profile Section for Mobile -->
          <div class="relative mb-4 pb-2 border-b border-gray-700/70">
            <!-- Avatar Button - Unique ID for mobile -->
            <button
              id="profile-menu-button-mobile"
              class="flex-shrink-0 focus:outline-none flex items-center space-x-3 w-full"
            >
              <img
                class="h-10 w-10 rounded-full object-cover ring-2 ring-[#f5141f] ring-offset-2 ring-offset-[#1A1C28]"
                src="https://placehold.co/100x100/f5141f/ffffff?text=U"
                onerror="this.onerror=null;this.src='https://placehold.co/100x100/334155/ffffff?text=U';"
                alt="User Avatar"
              />
              <span class="text-base font-semibold text-white">User Name</span>
              <!-- Down arrow icon (optional visual cue) -->
              <svg
                id="mobile-profile-arrow"
                class="h-5 w-5 text-gray-400 transform transition duration-200"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              >
                <polyline points="6 9 12 15 18 9"></polyline>
              </svg>
            </button>

            <!-- Profile Dropdown Menu for Mobile -->
            <div
              id="profile-menu-mobile"
              class="mt-3 w-full rounded-md py-1 bg-gray-800/80 hidden"
            >
              <a
                href="#"
                class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700/70 hover:text-white transition duration-150 flex items-center space-x-2"
              >
                <svg
                  class="h-4 w-4"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                >
                  <path d="M12 20h9"></path>
                  <path
                    d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"
                  ></path>
                </svg>
                <span>Settings</span>
              </a>
              <div class="border-t border-gray-700/70 my-1"></div>
              <a
                href="#"
                class="block px-4 py-2 text-sm text-red-400 hover:bg-gray-700/70 hover:text-red-300 transition duration-150 flex items-center space-x-2"
              >
                <svg
                  class="h-4 w-4"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                >
                  <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                  <polyline points="16 17 21 12 16 7"></polyline>
                  <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
                <span>Logout</span>
              </a>
            </div>
          </div>

          <!-- Mobile Navigation Links -->
          <a
            href="#"
            class="block px-3 py-2 rounded-md text-base font-medium text-white border-l-4 border-[#f61421]"
            >Home</a
          >
          <a
            href="#"
            class="block px-3 py-2 rounded-md text-base text-gray-300"
            >Calendar</a
          >
        </div>
      </div>
    </nav>

    <!-- JS for Menu Toggles and Search Logic -->
    <script>
      // DUMMY DATA for search results
      const DUMMY_DATA = [
        { id: 1, title: "Dashboard Overview", category: "Navigation" },
        { id: 2, title: "Team Management", category: "User" },
        { id: 3, title: "Project Alpha launch", category: "Projects" },
        { id: 4, title: "Client Reports Q4", category: "Reports" },
        { id: 5, title: "Calendar Events Sync", category: "Calendar" },
        { id: 6, title: "User Settings Profile", category: "Settings" },
        { id: 7, title: "Notification Preferences", category: "Settings" },
        { id: 8, title: "Billing and Invoices", category: "Finance" },
        { id: 9, title: "Data Security Policy", category: "Compliance" },
        { id: 10, title: "Marketing Strategy Review", category: "Projects" },
      ];

      /**
       * CORE FUNCTION: Calculates the Levenshtein distance (edit distance) between two strings.
       * This metric quantifies the similarity of two strings by counting the minimum
       * number of single-character edits (insertions, deletions, or substitutions)
       * required to change one word into the other. It is used for fuzzy matching.
       * @param {string} s1 - The first string.
       * @param {string} s2 - The second string.
       * @returns {number} The distance (number of edits required).
       */
      function levenshteinDistance(s1, s2) {
        s1 = s1.toLowerCase();
        s2 = s2.toLowerCase();

        const costs = [];
        for (let i = 0; i <= s1.length; i++) {
          let lastValue = i;
          for (let j = 0; j <= s2.length; j++) {
            if (i === 0) {
              costs[j] = j;
            } else if (j > 0) {
              let newValue = costs[j - 1];
              if (s1.charAt(i - 1) !== s2.charAt(j - 1)) {
                newValue =
                  Math.min(Math.min(newValue, lastValue), costs[j]) + 1;
              }
              costs[j - 1] = lastValue;
              lastValue = newValue;
            }
          }
          if (i > 0) costs[s2.length] = lastValue;
        }
        return costs[s2.length];
      }

      /**
       * CORE FUNCTION: Performs a multi-word, fuzzy search on the application's data.
       * The algorithm tokenizes the query and compares each query token against all
       * searchable target tokens (title + category), calculating a cumulative score
       * based on the best token matches found for each query word.
       * @param {string} query - The user's search input (e.g., "team managment").
       * @param {Array<Object>} data - The list of searchable objects (title, category).
       * @returns {Array<Object>} Filtered and sorted results, limited to 3 items (lowest score is best).
       */
      function fuzzySearch(query, data) {
        if (!query || query.length < 2) return [];

        const lowerQuery = query.toLowerCase();
        // Tokenize the user's input into separate search terms
        const queryTokens = lowerQuery.split(/\s+/).filter((t) => t.length > 0);

        return (
          data
            .map((item) => {
              // Combine title and category and tokenize the target words
              const searchTarget = item.title + " " + item.category;
              const targetTokens = searchTarget
                .toLowerCase()
                .split(/\s+/)
                .filter((t) => t.length > 0);

              let totalScore = 0;

              // 1. Give exact substring matches the highest priority (score 0)
              if (searchTarget.toLowerCase().includes(lowerQuery)) {
                return { ...item, score: 0 };
              }

              // 2. Calculate cumulative fuzzy score based on token-to-token matching
              for (const qToken of queryTokens) {
                let bestMatchDistance = Infinity;

                // Set a dynamic threshold (e.g., 1 error per 4 characters, min 1)
                const tokenThreshold = Math.floor(qToken.length / 4) + 1;

                // Find the best match distance for this query token against ALL target tokens
                for (const tToken of targetTokens) {
                  const distance = levenshteinDistance(qToken, tToken);
                  if (distance < bestMatchDistance) {
                    bestMatchDistance = distance;
                  }
                }

                // If the best match is within the threshold, add its distance to the total score
                if (bestMatchDistance <= tokenThreshold) {
                  totalScore += bestMatchDistance;
                } else {
                  // If any token doesn't match, this item is discarded
                  return null;
                }
              }

              // Return the item with the final cumulative fuzzy score
              return { ...item, score: totalScore };
            })
            // Filter out items that failed the token matching (returned null)
            .filter((item) => item !== null)
            // Sort by best match (lowest score/distance first)
            .sort((a, b) => a.score - b.score)
            // Limit recommendations to the maximum of 3 items
            .slice(0, 3)
        );
      }

      /**
       * HELPER FUNCTION: Renders the filtered search results into the dropdown container.
       * If no results are found, it displays a friendly message.
       * @param {Array<Object>} results - The search results with calculated scores.
       * @param {HTMLElement} resultsContainer - The DOM element to fill with results.
       */
      function renderResults(results, resultsContainer) {
        if (results.length === 0) {
          resultsContainer.innerHTML =
            '<div class="px-4 py-3 text-gray-500 text-sm">No fuzzy matches found. Try a different spelling.</div>';
        } else {
          resultsContainer.innerHTML = results
            .map(
              (item) => `
                <a href="#" class="flex justify-between items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700/70 transition duration-150 rounded-lg m-1">
                    <span class="truncate">${item.title}</span>
                    <span class="text-xs text-white bg-gray-700/50 px-2 py-0.5 rounded-full">${item.category}</span>
                </a>
            `
            )
            .join("");
        }
        resultsContainer.classList.remove("hidden");
      }

      document.addEventListener("DOMContentLoaded", () => {
        // --- DOM Element References ---
        const searchInput = document.getElementById("search-input");
        const searchResults = document.getElementById("search-results");
        const mobileMenu = document.getElementById("mobile-menu");
        const hamburgerIcon = document.getElementById("hamburger-icon");
        const closeIcon = document.getElementById("close-icon");

        // --- 1. Search Logic Setup ---

        /**
         * Event handler for search input changes (on 'input' event).
         * Triggers the fuzzy search and updates the result dropdown.
         * @param {Event} event - The input event object.
         */
        const handleSearch = (event) => {
          const query = event.target.value.trim();

          if (query.length < 2) {
            searchResults.innerHTML = "";
            searchResults.classList.add("hidden");
            return;
          }

          const results = fuzzySearch(query, DUMMY_DATA);
          renderResults(results, searchResults);
        };

        // Attach the search handler to the input field
        searchInput.addEventListener("input", handleSearch);

        // --- 2. Mobile Menu Setup ---

        /**
         * Sets up the click listener for the mobile hamburger button to toggle the menu.
         */
        const setupMobileMenuToggle = () => {
          const mobileButton = document.getElementById("mobile-menu-button");
          if (mobileButton) {
            mobileButton.addEventListener("click", () => {
              mobileMenu.classList.toggle("menu-open");
              hamburgerIcon.classList.toggle("hidden");
              closeIcon.classList.toggle("hidden");
            });
          }
        };

        // --- 3. Profile Dropdown Setup ---

        /**
         * Sets up the click listeners for both desktop and mobile profile menus.
         */
        const setupProfileDropdowns = () => {
          // Desktop Profile Menu Toggle
          const profileButtonDesktop = document.getElementById(
            "profile-menu-button-desktop"
          );
          const profileMenuDesktop = document.getElementById(
            "profile-menu-desktop"
          );
          if (profileButtonDesktop) {
            profileButtonDesktop.addEventListener("click", () => {
              profileMenuDesktop.classList.toggle("hidden");
            });
          }

          // Mobile Profile Menu Toggle
          const profileButtonMobile = document.getElementById(
            "profile-menu-button-mobile"
          );
          const profileMenuMobile = document.getElementById(
            "profile-menu-mobile"
          );
          const mobileProfileArrow = document.getElementById(
            "mobile-profile-arrow"
          );

          if (profileButtonMobile) {
            profileButtonMobile.addEventListener("click", (event) => {
              event.preventDefault();
              profileMenuMobile.classList.toggle("hidden");
              mobileProfileArrow.classList.toggle("rotate-180");
            });
          }
        };

        // --- 4. Outside Click Handler Setup ---

        /**
         * Sets up the global click listener to close open menus and search results
         * when the user clicks anywhere outside of them.
         */
        const setupOutsideClickClose = () => {
          const profileButtonDesktop = document.getElementById(
            "profile-menu-button-desktop"
          );
          const profileMenuDesktop = document.getElementById(
            "profile-menu-desktop"
          );

          document.addEventListener("click", (event) => {
            // Close Search Results dropdown if click is outside search bar/results area
            const isInsideSearch =
              searchInput.contains(event.target) ||
              searchResults.contains(event.target);
            if (!isInsideSearch) {
              searchResults.classList.add("hidden");
            }

            // Close Desktop Profile Dropdown if click is outside button/menu area
            if (profileButtonDesktop && profileMenuDesktop) {
              if (
                !profileButtonDesktop.contains(event.target) &&
                !profileMenuDesktop.contains(event.target)
              ) {
                if (!profileMenuDesktop.classList.contains("hidden")) {
                  profileMenuDesktop.classList.add("hidden");
                }
              }
            }
          });
        };

        // --- Execute Initialization Functions ---
        setupMobileMenuToggle();
        setupProfileDropdowns();
        setupOutsideClickClose();
      });
    </script>