/**
 * script.js
 *
 * This script handles the dynamic loading, filtering, and pagination of barter offers
 * and requests on the TradeTide platform. It communicates with a PHP backend API
 * to fetch data and renders it as interactive cards on the page.
 */

// --- 1. Constants for DOM Elements ---
// Selectors for elements on the page that this script will interact with.
const bartersContainer = document.getElementById('barters-container');
const bartersMessage = document.getElementById('barters-message');
const filterForm = document.getElementById('filter-form');
const resultsCountSpan = document.getElementById('results-count');
const loadMoreButton = document.getElementById('load-more-btn');
const clearFiltersButton = document.getElementById('clear-filters-btn'); // Assuming you'll add this button

// --- 2. Configuration ---
// Global settings for the API endpoint and pagination.
// IMPORTANT: Replace 'api/get_barters.php' with the actual, correct path to your PHP endpoint!
const BARTERS_API_ENDPOINT = 'api/get_barters.php';
let currentPage = 1;      // Tracks the current page number for pagination.
const itemsPerPage = 6;   // Defines how many barters to load per page.

// --- 3. UI Feedback Functions ---

/**
 * Displays a dynamic message in the designated feedback area (bartersMessage).
 * The message will be styled based on its type.
 * @param {string} message The text content of the message to display.
 * @param {'loading' | 'error' | 'info' | 'success'} type The category of the message,
 * influencing its visual styling.
 */
function showMessage(message, type = 'info') {
    // Reset classes to ensure only the new type's class is applied
    bartersMessage.classList.remove('hidden', 'text-green-600', 'text-red-600', 'text-blue-600', 'text-gray-600');
    bartersMessage.textContent = message;

    switch (type) {
        case 'loading':
            bartersMessage.classList.add('text-blue-600');
            break;
        case 'error':
            bartersMessage.classList.add('text-red-600');
            break;
        case 'success':
            bartersMessage.classList.add('text-green-600');
            break;
        case 'info':
        default:
            bartersMessage.classList.add('text-gray-600');
            break;
    }
    // Ensure the message is visible
    bartersMessage.classList.remove('hidden');
}

/**
 * Hides the message feedback area.
 */
function hideMessage() {
    bartersMessage.classList.add('hidden');
    bartersMessage.textContent = ''; // Clear content when hidden
}

/**
 * Manages the loading state of interactive buttons.
 * @param {HTMLButtonElement} button The button element to manage.
 * @param {boolean} isLoading True to set loading state, false to disable.
 */
function setButtonLoading(button, isLoading) {
    if (isLoading) {
        button.disabled = true;
        button.classList.add('opacity-50', 'cursor-not-allowed'); // Add Tailwind classes for disabled state
        // Optionally, change text to 'Loading...' or add a spinner
        // button.textContent = 'Loading...';
    } else {
        button.disabled = false;
        button.classList.remove('opacity-50', 'cursor-not-allowed');
        // Optionally, restore original text
        // button.textContent = 'Load More';
    }
}


// --- 4. Barter Card Rendering ---

/**
 * Generates the HTML string for a single barter card.
 * @param {object} barter The barter data object (e.g., {id, title, description, type, category, location, image_url}).
 * @returns {string} An HTML string representing the barter card.
 */
function createBarterCard(barter) {
    // Determine the type class for offer/request badges
    const typeClass = barter.type === 'offer'
        ? 'bg-green-100 text-green-800'
        : 'bg-orange-100 text-orange-800';

    // Conditionally render an image or a default SVG placeholder
    const imageHtml = barter.image_url
        ? `<img src="${barter.image_url}" alt="${barter.title}" class="w-full h-48 object-cover">`
        : `<div class="h-48 bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
               <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a4 4 0 004-4V5z"></path>
               </svg>
           </div>`;

    // Truncate description for card display
    const truncatedDescription = barter.description.length > 100
        ? `${barter.description.substring(0, 100)}...`
        : barter.description;

    return `
        <article class="card"> ${imageHtml}
            <div class="p-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="${typeClass} px-2 py-1 rounded-full text-xs font-medium capitalize">${barter.type}</span>
                    <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-xs font-medium">${barter.category}</span>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">${barter.title}</h3>
                <p class="text-gray-600 mb-4">${truncatedDescription}</p>
                <div class="flex items-center justify-between">
                    <div class="flex items-center text-sm text-gray-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        ${barter.location || 'Remote'}
                    </div>
                    <a href="barter-details.html?id=${barter.id}" class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-4 py-2 rounded-md font-medium transition-all duration-200">
                        View Details
                    </a>
                </div>
            </div>
        </article>
    `;
}

// --- 5. Data Fetching and Rendering Core Logic ---

/**
 * Fetches barter data from the API based on current filters and pagination,
 * then renders the results to the DOM.
 * @param {URLSearchParams} params URL search parameters derived from the filter form.
 * @param {boolean} append If true, new barters are appended to existing ones;
 * otherwise, the container is cleared and new results replace old.
 */
async function fetchAndRenderBarters(params = new URLSearchParams(), append = false) {
    if (!append) {
        bartersContainer.innerHTML = ''; // Clear existing content if not appending
        showMessage('Loading barters...', 'loading');
        resultsCountSpan.textContent = 'Loading...';
        setButtonLoading(loadMoreButton, true); // Disable load more button
    } else {
        showMessage('Loading more barters...', 'loading');
        setButtonLoading(loadMoreButton, true); // Disable load more button
    }

    try {
        // Add pagination parameters to the request
        params.set('page', currentPage);
        params.set('limit', itemsPerPage);

        const response = await fetch(`${BARTERS_API_ENDPOINT}?${params.toString()}`);

        if (!response.ok) {
            // Log full error response if available for debugging
            const errorText = await response.text();
            console.error(`HTTP error! Status: ${response.status}, Details: ${errorText}`);
            throw new Error(`Server error: ${response.status} ${response.statusText}`);
        }

        const data = await response.json(); // Parse the JSON response

        // Crucial: Check for a 'success' flag and 'message' from your PHP API
        if (data.success) {
            const barters = data.barters;
            const totalResults = data.total; // Total count of barters available on the backend

            if (barters.length === 0 && !append) {
                // No barters found for initial load or new filters
                showMessage('No barters found matching your criteria.', 'info');
                resultsCountSpan.textContent = '0 results';
                loadMoreButton.classList.add('hidden');
            } else {
                hideMessage(); // Hide loading message once results are here
                barters.forEach(barter => {
                    bartersContainer.insertAdjacentHTML('beforeend', createBarterCard(barter));
                });

                resultsCountSpan.textContent = `Showing ${bartersContainer.children.length} of ${totalResults} results`;

                // Determine whether to show/hide the load more button
                if (bartersContainer.children.length < totalResults) {
                    loadMoreButton.classList.remove('hidden');
                } else {
                    loadMoreButton.classList.add('hidden');
                    showMessage('All barters loaded.', 'success'); // Indicate all items loaded
                }
            }
        } else {
            // Handle specific errors returned by your PHP API (e.g., validation errors)
            showMessage(`Error: ${data.message || 'Failed to fetch barters due to an API issue.'}`, 'error');
            resultsCountSpan.textContent = 'Error';
            loadMoreButton.classList.add('hidden');
        }

    } catch (error) {
        console.error('Fetch error:', error);
        showMessage('Failed to load barters. Please check your internet connection or try again later.', 'error');
        resultsCountSpan.textContent = 'Error';
        loadMoreButton.classList.add('hidden'); // Hide load more on network/unexpected error
    } finally {
        setButtonLoading(loadMoreButton, false); // Always re-enable button after fetch attempt
    }
}

/**
 * Gathers current filter values from the form and returns them as URLSearchParams.
 * @returns {URLSearchParams} Parameters object containing current filter values.
 */
function getFilterParams() {
    const formData = new FormData(filterForm);
    const params = new URLSearchParams();
    for (const [key, value] of formData.entries()) {
        if (value && value.trim() !== '') { // Only add if value is not empty or just whitespace
            params.append(key, value.trim());
        }
    }
    return params;
}


// --- 6. Event Listeners ---

/**
 * Initializes the page by fetching the first set of barters.
 * This runs as soon as the DOM is fully loaded.
 */
document.addEventListener('DOMContentLoaded', () => {
    fetchAndRenderBarters();

    // Attach event listener for the Clear Filters button if it exists
    if (clearFiltersButton) {
        clearFiltersButton.addEventListener('click', () => {
            filterForm.reset(); // Resets all form fields to their default values
            currentPage = 1; // Reset pagination
            fetchAndRenderBarters(new URLSearchParams(), false); // Fetch all barters without filters
        });
    }
});

/**
 * Handles the submission of the filter form.
 * Prevents default form submission and re-fetches barters based on new filters.
 */
filterForm.addEventListener('submit', async function(event) {
    event.preventDefault(); // Prevent page reload
    currentPage = 1; // Reset to the first page for new filters
    const params = getFilterParams(); // Get filter parameters from the form
    fetchAndRenderBarters(params, false); // Fetch and replace results
});

/**
 * Handles clicks on the "Load More" button.
 * Increments the page number and fetches the next set of barters, appending them.
 */
loadMoreButton.addEventListener('click', () => {
    currentPage++; // Move to the next page
    const params = getFilterParams(); // Get current filter values to apply to the next page
    fetchAndRenderBarters(params, true); // Fetch and append results
});

// --- 7. Client-Side Navigation & Authentication Check (Placeholder) ---
/*
 * This section is a placeholder for shared logic that would typically be used
 * across multiple pages to manage user authentication state.
 *
 * It's crucial to have a robust server-side session management for security.
 * Client-side checks are for UX only (e.g., showing/hiding links, redirecting unauthenticated users).
 *
 * Ideally, this check would live in a separate, globally included script
 * (e.g., 'auth.js' or 'main.js') if you have many pages requiring it.
 */

/**
 * Asynchronously checks the user's login status against a PHP endpoint.
 * @returns {Promise<boolean>} A promise that resolves to true if logged in, false otherwise.
 */
async function checkLoginStatus() {
    // IMPORTANT: Replace this with your actual PHP endpoint to check login status
    const CHECK_STATUS_ENDPOINT = 'api/check_session.php'; // Example: returns JSON { logged_in: true/false }

    try {
        const response = await fetch(CHECK_STATUS_ENDPOINT);
        if (!response.ok) {
            console.error('Session check failed on server:', response.status, response.statusText);
            return false; // Assume not logged in on server error
        }
        const data = await response.json();
        return data.logged_in; // Expecting { logged_in: true/false }
    } catch (error) {
        console.error('Network error during session check:', error);
        return false; // Assume not logged in on network/fetch error
    }
}

// Example usage on a protected page (e.g., 'dashboard.html', 'mybarters.html'):
// document.addEventListener('DOMContentLoaded', async () => {
//     const isLoggedIn = await checkLoginStatus();
//     if (!isLoggedIn) {
//         window.location.href = 'login.html'; // Redirect to login page if not authenticated
//     }
//     // If logged in, proceed with page-specific data fetching (like initial barters load)
// });

// For 'browsebarters.html', viewing barters generally doesn't require login,
// but actions like creating a new barter or viewing messages would.
// The navigation links (Dashboard, My Barters, Messages) would be dynamically
// shown/hidden based on the login status checked by a global script.