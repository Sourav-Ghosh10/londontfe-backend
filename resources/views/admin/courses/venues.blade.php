@extends('admin.layout')

@section('content')
<div class="w-full">

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Venue Management</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Create, edit and manage global training course venues and seals status</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="/admin/courses/venues/create" class="flex items-center gap-1.5 text-sm font-medium text-white bg-[#008060] hover:bg-[#006e52] px-4 py-2 rounded-md transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#008060]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                + Add Venue
            </a>
        </div>
    </div>

    <!-- Filters and Actions Toolbar -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xs border border-gray-250 dark:border-gray-700 p-5 mb-6 transition-colors">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
            
            <!-- Region Filter -->
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">Region</label>
                <div class="relative">
                    <select id="region-filter" onchange="filterVenues()" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors appearance-none cursor-pointer">
                        <option value="all">All Regions</option>
                        <option value="Europe">Europe</option>
                        <option value="Middle East">Middle East</option>
                        <option value="Rest of World">Rest of World</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">Search Venues</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                        </svg>
                    </span>
                    <input type="text" id="venue-search" oninput="filterVenues()" placeholder="Search by venue name or country..." class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md pl-10 pr-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors">
                </div>
            </div>

        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-300 dark:border-gray-700 overflow-hidden transition-colors">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs font-semibold text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/40 uppercase border-b border-gray-300 dark:border-gray-700 transition-colors">
                    <tr>
                        <th class="px-6 py-4">Venue name</th>
                        <th class="px-6 py-4">Flag image</th>
                        <th class="px-6 py-4">Region</th>
                        <th class="px-6 py-4 text-right w-44">Actions</th>
                    </tr>
                </thead>
                <tbody id="venues-table-body" class="divide-y divide-gray-200 dark:divide-gray-700 transition-colors">
                    <!-- Loaded dynamically via Javascript for interactive demo -->
                </tbody>
            </table>
        </div>

        <!-- Empty State -->
        <div id="empty-state" class="hidden py-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No venues found</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Try adjusting your filters or search terms.</p>
        </div>

        <!-- Table Footer/Pagination Summary -->
        <div class="px-6 py-4 border-t border-gray-300 dark:border-gray-700 flex items-center justify-between transition-colors bg-gray-50 dark:bg-gray-900/10">
            <p class="text-sm text-gray-500 dark:text-gray-400" id="table-summary">
                Showing <span class="font-semibold text-gray-900 dark:text-white" id="displayed-count">0</span> of <span class="font-semibold text-gray-900 dark:text-white" id="total-count">0</span> venues
            </p>
            <div class="text-xs text-gray-400 dark:text-gray-500">
                Mock Venue Management System
            </div>
        </div>
    </div>
</div>

<!-- ================= MODALS ================= -->

<!-- Edit Venue Modal -->
<div id="edit-venue-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="edit-venue-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-black dark:bg-opacity-80 transition-opacity" aria-hidden="true" onclick="closeEditVenueModal()"></div>
        
        <!-- Center element trick -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal panel -->
        <div class="relative inline-block align-middle bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-300 dark:border-gray-700">
            <form onsubmit="handleEditVenue(event)">
                <input type="hidden" id="edit-venue-id">
                <div class="bg-white dark:bg-gray-800 px-6 pt-6 pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-bold text-gray-900 dark:text-white" id="edit-venue-title">
                                Edit Venue
                            </h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label for="edit-venue-name" class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-2">Venue Name</label>
                                    <input type="text" id="edit-venue-name" required class="w-full text-sm bg-gray-50 dark:bg-gray-750 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-250 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#008060] focus:border-[#008060] transition-colors" placeholder="e.g. Athens">
                                </div>
                                <div>
                                    <label for="edit-venue-flag" class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-2">Flag / Country Name</label>
                                    <input type="text" id="edit-venue-flag" required class="w-full text-sm bg-gray-50 dark:bg-gray-750 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-250 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#008060] focus:border-[#008060] transition-colors" placeholder="e.g. Greece">
                                </div>
                                <div>
                                    <label for="edit-venue-region" class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-2">Region</label>
                                    <select id="edit-venue-region" class="w-full text-sm bg-gray-50 dark:bg-gray-750 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-250 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#008060] focus:border-[#008060] transition-colors">
                                        <option value="Europe">Europe</option>
                                        <option value="Middle East">Middle East</option>
                                        <option value="Rest of World">Rest of World</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900/40 px-6 py-3.5 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#008060] text-base font-medium text-white hover:bg-[#006e52] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#008060] sm:ml-3 sm:w-auto sm:text-sm">
                        Save Changes
                    </button>
                    <button type="button" onclick="closeEditVenueModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-250 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#008060] sm:mt-0 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-venue-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="delete-venue-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-black dark:bg-opacity-80 transition-opacity" aria-hidden="true" onclick="closeDeleteVenueModal()"></div>
        
        <!-- Center element trick -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal panel -->
        <div class="relative inline-block align-middle bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-300 dark:border-gray-700">
            <div class="bg-white dark:bg-gray-800 px-6 pt-6 pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-bold text-gray-900 dark:text-white" id="delete-venue-title">
                            Delete Venue
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Are you sure you want to delete <span id="delete-venue-name-display" class="font-semibold text-gray-800 dark:text-gray-250"></span>? This will permanently remove the venue from your dashboard and web catalog. This action cannot be undone.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-900/40 px-6 py-3.5 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                <button type="button" onclick="confirmDeleteVenue()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Delete
                </button>
                <button type="button" onclick="closeDeleteVenueModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-250 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#008060] sm:mt-0 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Premium Toast Notifications -->
<div id="toast" class="fixed bottom-5 right-5 z-50 transform translate-y-24 opacity-0 transition-all duration-300 flex items-center gap-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-3 rounded-lg shadow-xl max-w-sm">
    <div id="toast-icon-wrapper" class="rounded-full p-1 bg-green-500 text-white">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
    </div>
    <span id="toast-message" class="text-sm font-semibold">Action completed successfully!</span>
</div>

<!-- ================= JAVASCRIPT ================= -->
<script>
    // Prepopulated Venue Database matching exact screenshots
    let venues = [
        { id: 1, name: "Athens", image: "https://images.unsplash.com/photo-1603565816030-6b389eeb23cb?auto=format&fit=crop&w=120&q=80", flag: "Greece", region: "Europe", featured: false, sealsStatus: true },
        { id: 2, name: "Dubai", image: "https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=120&q=80", flag: "United Arab Emirates", region: "Middle East", featured: true, sealsStatus: true },
        { id: 3, name: "Vienna", image: "https://images.unsplash.com/photo-1516550893923-42d28e5677af?auto=format&fit=crop&w=120&q=80", flag: "Austria", region: "Europe", featured: false, sealsStatus: true },
        { id: 4, name: "London", image: "https://images.unsplash.com/photo-1513635269975-59663e0ac1ad?auto=format&fit=crop&w=120&q=80", flag: "United Kingdom", region: "Europe", featured: false, sealsStatus: true },
        { id: 5, name: "Copenhagen", image: "https://images.unsplash.com/photo-1513622470522-26c3c8a854bc?auto=format&fit=crop&w=120&q=80", flag: "Denmark", region: "Europe", featured: false, sealsStatus: true },
        { id: 6, name: "Budapest", image: "https://images.unsplash.com/photo-1565426960434-08f1b621eefb?auto=format&fit=crop&w=120&q=80", flag: "Hungary", region: "Europe", featured: false, sealsStatus: true },
        { id: 7, name: "Stockholm", image: "https://images.unsplash.com/photo-1509142168808-57d19c9e3650?auto=format&fit=crop&w=120&q=80", flag: "Sweden", region: "Europe", featured: false, sealsStatus: true },
        { id: 8, name: "Istanbul", image: "https://images.unsplash.com/photo-1524231757912-21f4fe3a7200?auto=format&fit=crop&w=120&q=80", flag: "Turkey", region: "Europe", featured: false, sealsStatus: true },
        { id: 9, name: "Kuala Lumpur", image: "https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&w=120&q=80", flag: "Malaysia", region: "Rest of World", featured: false, sealsStatus: true }
    ];

    let deleteId = null;

    // Initialize Table
    document.addEventListener("DOMContentLoaded", () => {
        renderTable();
    });

    // Render Table Content
    function renderTable() {
        const tbody = document.getElementById("venues-table-body");
        const emptyState = document.getElementById("empty-state");
        const searchVal = document.getElementById("venue-search").value.toLowerCase().trim();
        const regionVal = document.getElementById("region-filter").value;

        // Filter database
        const filtered = venues.filter(v => {
            const matchesSearch = v.name.toLowerCase().includes(searchVal) || v.flag.toLowerCase().includes(searchVal);
            
            let matchesRegion = true;
            if (regionVal !== "all") {
                matchesRegion = v.region === regionVal;
            }

            return matchesSearch && matchesRegion;
        });

        // Clear Table
        tbody.innerHTML = "";

        if (filtered.length === 0) {
            emptyState.classList.remove("hidden");
        } else {
            emptyState.classList.add("hidden");
            filtered.forEach(v => {
                const tr = document.createElement("tr");
                tr.className = "hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors group text-gray-900 dark:text-gray-200";

                tr.innerHTML = `
                    <td class="px-6 py-4 font-semibold text-gray-950 dark:text-white text-[15px]">${v.name}</td>
                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400 font-medium">${v.flag}</td>
                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">${v.region}</td>
                    <td class="px-6 py-4 text-right whitespace-nowrap">
                        <div class="flex items-center justify-end gap-2.5 opacity-90 group-hover:opacity-100 transition-opacity">
                            <a href="/admin/courses/venues/view?id=${v.id}" title="View Venue Details" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-950/20 rounded-full border border-gray-250 dark:border-gray-600 transition-colors focus:outline-none shadow-xs bg-white dark:bg-gray-800">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                            <button onclick="openEditVenueModal(${v.id})" title="Edit Venue" class="p-1.5 text-gray-400 hover:text-[#008060] hover:bg-emerald-55 dark:hover:bg-emerald-950/20 rounded-full border border-gray-250 dark:border-gray-600 transition-colors focus:outline-none shadow-xs bg-white dark:bg-gray-800">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                </svg>
                            </button>
                            <button onclick="openDeleteVenueModal(${v.id})" title="Delete Venue" class="p-1.5 text-gray-400 hover:text-red-650 hover:bg-red-50 dark:hover:bg-red-950/20 rounded-full border border-gray-250 dark:border-gray-600 transition-colors focus:outline-none shadow-xs bg-white dark:bg-gray-800">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        // Update counts
        document.getElementById("displayed-count").textContent = filtered.length;
        document.getElementById("total-count").textContent = venues.length;
    }

    // Trigger filters on input
    function filterVenues() {
        renderTable();
    }

    // Modal Control: Edit Venue
    function openEditVenueModal(id) {
        const v = venues.find(item => item.id === id);
        if (v) {
            document.getElementById("edit-venue-id").value = v.id;
            document.getElementById("edit-venue-name").value = v.name;
            document.getElementById("edit-venue-flag").value = v.flag;
            document.getElementById("edit-venue-region").value = v.region;
            document.getElementById("edit-venue-modal").classList.remove("hidden");
        }
    }

    function closeEditVenueModal() {
        document.getElementById("edit-venue-modal").classList.add("hidden");
    }

    function handleEditVenue(e) {
        e.preventDefault();
        const idVal = parseInt(document.getElementById("edit-venue-id").value);
        const nameVal = document.getElementById("edit-venue-name").value.trim();
        const flagVal = document.getElementById("edit-venue-flag").value.trim();
        const regionVal = document.getElementById("edit-venue-region").value;

        const v = venues.find(item => item.id === idVal);
        if (v && nameVal && flagVal) {
            v.name = nameVal;
            v.flag = flagVal;
            v.region = regionVal;
            closeEditVenueModal();
            renderTable();
            showToast(`Venue updated successfully!`, "success");
        }
    }

    // Modal Control: Delete Venue
    function openDeleteVenueModal(id) {
        const v = venues.find(item => item.id === id);
        if (v) {
            deleteId = id;
            document.getElementById("delete-venue-name-display").textContent = `"${v.name}"`;
            document.getElementById("delete-venue-modal").classList.remove("hidden");
        }
    }

    function closeDeleteVenueModal() {
        document.getElementById("delete-venue-modal").classList.add("hidden");
        deleteId = null;
    }

    function confirmDeleteVenue() {
        if (deleteId) {
            const index = venues.findIndex(item => item.id === deleteId);
            if (index !== -1) {
                const deletedName = venues[index].name;
                venues.splice(index, 1);
                closeDeleteVenueModal();
                renderTable();
                showToast(`Venue "${deletedName}" deleted!`, "error");
            }
        }
    }

    // Toast Control
    function showToast(message, type = "success") {
        const toast = document.getElementById("toast");
        const toastMsg = document.getElementById("toast-message");
        const toastIconWrapper = document.getElementById("toast-icon-wrapper");

        toastMsg.textContent = message;

        if (type === "success") {
            toastIconWrapper.className = "rounded-full p-1 bg-green-500 text-white";
            toastIconWrapper.innerHTML = `
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            `;
        } else if (type === "error") {
            toastIconWrapper.className = "rounded-full p-1 bg-red-500 text-white";
            toastIconWrapper.innerHTML = `
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            `;
        }

        // Slide in
        toast.classList.remove("translate-y-24", "opacity-0");
        toast.classList.add("translate-y-0", "opacity-100");

        // Slide out
        setTimeout(() => {
            toast.classList.add("translate-y-24", "opacity-0");
            toast.classList.remove("translate-y-0", "opacity-100");
        }, 3000);
    }
</script>

<style>
    /* Sleek custom styling rules */
    .dark .bg-gray-750 {
        background-color: #2a2e35;
    }
    .dark .text-gray-250 {
        color: #e2e8f0;
    }
</style>
@endsection
