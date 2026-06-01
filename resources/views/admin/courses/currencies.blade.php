@extends('admin.layout')

@section('content')
<!-- Page Document Title for PJAX Route Parser -->
<title>Currency Rates - London TFE Admin</title>

<div class="w-full space-y-6">

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-2">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Currency Rates</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Configure exchange rates and set your platform's base currency.</p>
        </div>
        <!-- Breadcrumb -->
        <div class="flex items-center gap-1 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
            <span>Course</span>
            <svg class="w-3 h-3 text-gray-350" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-gray-700 dark:text-gray-300">Currency Rates</span>
        </div>
    </div>

    <!-- Configuration Card -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-250 dark:border-gray-700 shadow-xs overflow-hidden transition-all duration-200">
        <!-- Card Header -->
        <div class="px-6 py-4.5 border-b border-gray-150 dark:border-gray-755 bg-gray-50/50 dark:bg-gray-900/30 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white">Currency configuration</h2>
                    <p class="text-xs text-gray-450 dark:text-gray-450">Set base currency and configure live multipliers</p>
                </div>
            </div>
            <!-- Status Indicator Badge -->
            <span id="save-badge" class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xxs font-bold uppercase tracking-wider bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400">
                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                Synchronized
            </span>
        </div>

        <form id="currency-form" onsubmit="handleUpdate(event)" class="p-6">
            <div class="space-y-6">
                <!-- Base Currency Selector -->
                <div class="space-y-2 border-b border-gray-100 dark:border-gray-750 pb-6">
                    <div>
                        <label for="base-currency" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Base Currency</label>
                        <span class="block text-xxs text-gray-400 dark:text-gray-500 mt-0.5 font-medium">Primary currency for pricing</span>
                    </div>
                    <div class="relative max-w-xl">
                        <select id="base-currency" onchange="changeBaseCurrency(this.value)" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors cursor-pointer appearance-none">
                            <option value="GBP">British pound (&pound;)</option>
                            <option value="USD">US Dollar ($)</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Rates Section -->
                <div class="space-y-6">
                    
                    <!-- British Pound Row -->
                    <div class="space-y-2">
                        <div>
                            <label for="rate-gbp" class="flex items-center gap-1.5 text-xs font-bold text-gray-750 dark:text-gray-300 uppercase tracking-wider">
                                <span class="inline-flex items-center justify-center w-5 h-5 text-xxs font-extrabold rounded bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400">&pound;</span>
                                British Pound
                            </label>
                            <span class="block text-xxs text-gray-400 dark:text-gray-500 mt-0.5 font-medium">GBP / Base Multiplier</span>
                        </div>
                        <div class="max-w-xl">
                            <div class="relative">
                                <input type="text" id="rate-gbp" oninput="validateInput('rate-gbp')" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 pr-12 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors font-mono" placeholder="1.000000">
                                <span class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-xs font-semibold text-gray-500 pointer-events-none">GBP</span>
                            </div>
                            <div id="err-rate-gbp" class="hidden text-xxs text-red-500 font-semibold mt-1">Please enter a valid positive decimal value (e.g. 1.25).</div>
                            <p class="text-xxs text-gray-450 dark:text-gray-500 mt-2 leading-relaxed flex items-start gap-1 font-medium">
                                <span class="text-[#008060] font-bold">Input Rules:</span> 
                                <span>1. Input should be in decimal format.(E.g. 0.75 / 0.84) &nbsp;2. E.g. 1 GBP = 1.3 Dollar</span>
                            </p>
                        </div>
                    </div>

                    <!-- US Dollar Row -->
                    <div class="space-y-2 pt-2">
                        <div>
                            <label for="rate-usd" class="flex items-center gap-1.5 text-xs font-bold text-gray-750 dark:text-gray-300 uppercase tracking-wider">
                                <span class="inline-flex items-center justify-center w-5 h-5 text-xxs font-extrabold rounded bg-emerald-55 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400">$</span>
                                US Dollar
                            </label>
                            <span class="block text-xxs text-gray-400 dark:text-gray-500 mt-0.5 font-medium">USD / Base Multiplier</span>
                        </div>
                        <div class="max-w-xl">
                            <div class="relative">
                                <input type="text" id="rate-usd" oninput="validateInput('rate-usd')" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 pr-12 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors font-mono" placeholder="1.240000">
                                <span class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-xs font-semibold text-gray-500 pointer-events-none">USD</span>
                            </div>
                            <div id="err-rate-usd" class="hidden text-xxs text-red-500 font-semibold mt-1">Please enter a valid positive decimal value (e.g. 1.25).</div>
                            <p class="text-xxs text-gray-450 dark:text-gray-500 mt-2 leading-relaxed flex items-start gap-1 font-medium">
                                <span class="text-[#008060] font-bold">Input Rules:</span>
                                <span>1. Input should be in decimal format.(E.g. 0.75 / 0.84) &nbsp;2. E.g. 1 USD = 0.77 GBP</span>
                            </p>
                        </div>
                    </div>

                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-start gap-3 pt-6 border-t border-gray-250 dark:border-gray-700 max-w-xl">
                    <button type="submit" id="update-btn" class="flex items-center gap-1.5 px-5 py-2.5 text-sm font-semibold text-white bg-[#008060] hover:bg-[#006e52] rounded-md transition-colors shadow-xs focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#008060] cursor-pointer">
                        <svg id="loading-spinner" class="hidden animate-spin -ml-1 mr-1 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Update Rates</span>
                    </button>
                    <button type="button" onclick="resetRates()" class="px-5 py-2.5 border border-gray-300 dark:border-gray-650 text-sm font-semibold rounded-md text-gray-700 dark:text-gray-250 bg-white dark:bg-gray-750 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#008060] transition-colors cursor-pointer">
                        Cancel
                    </button>
                </div>
            </div>
        </form>
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

<!-- ================= JAVASCRIPT LOGIC ================= -->
<script>
    // Live in-memory / LocalStorage State Management
    let ratesConfig = {
        baseCurrency: "GBP",
        rates: {
            GBP: 1.000000,
            USD: 1.240000
        }
    };

    // Initialize configuration
    document.addEventListener("DOMContentLoaded", () => {
        loadSavedRates();
    });

    function loadSavedRates() {
        const saved = localStorage.getItem("londontfe_currency_rates");
        if (saved) {
            try {
                ratesConfig = JSON.parse(saved);
                console.log("Loaded existing rates from local storage:", ratesConfig);
            } catch (e) {
                console.error("Error parsing stored currency rates", e);
            }
        }
        
        // Pre-fill form inputs
        document.getElementById("base-currency").value = ratesConfig.baseCurrency;
        
        // Fill input rate values
        document.getElementById("rate-gbp").value = Number(ratesConfig.rates.GBP).toFixed(6);
        document.getElementById("rate-usd").value = Number(ratesConfig.rates.USD).toFixed(6);
        
        // Trigger base currency formatting rules
        toggleBaseDisabledState();
    }

    function toggleBaseDisabledState() {
        const base = ratesConfig.baseCurrency;
        const gbpInput = document.getElementById("rate-gbp");
        const usdInput = document.getElementById("rate-usd");

        // Enable all first
        gbpInput.disabled = false;
        usdInput.disabled = false;

        gbpInput.className = "w-full text-sm bg-gray-50 dark:bg-gray-750 border border-gray-300 dark:border-gray-650 text-gray-900 dark:text-gray-100 rounded-md px-3.5 py-2.5 pr-10 focus:outline-none focus:ring-2 focus:ring-[#008060] focus:border-[#008060] transition-all duration-200 font-mono";
        usdInput.className = "w-full text-sm bg-gray-50 dark:bg-gray-750 border border-gray-300 dark:border-gray-650 text-gray-900 dark:text-gray-100 rounded-md px-3.5 py-2.5 pr-10 focus:outline-none focus:ring-2 focus:ring-[#008060] focus:border-[#008060] transition-all duration-200 font-mono";

        // Disable base currency input as it has to be exactly 1.0
        if (base === "GBP") {
            gbpInput.value = "1.000000";
            gbpInput.disabled = true;
            gbpInput.className += " opacity-60 bg-gray-200/50 dark:bg-gray-800/80 cursor-not-allowed border-gray-200 dark:border-gray-700";
        } else if (base === "USD") {
            usdInput.value = "1.000000";
            usdInput.disabled = true;
            usdInput.className += " opacity-60 bg-gray-200/50 dark:bg-gray-800/80 cursor-not-allowed border-gray-200 dark:border-gray-700";
        }
    }

    function changeBaseCurrency(newBase) {
        // Automatically calculate inverse rates for natural base currency transitions!
        const prevBase = ratesConfig.baseCurrency;
        if (prevBase === newBase) return;

        // Extract raw decimal values currently typed in inputs
        let valGBP = parseFloat(document.getElementById("rate-gbp").value) || 1;
        let valUSD = parseFloat(document.getElementById("rate-usd").value) || 1.24;

        // Transition calculations
        let conversionFactor = 1;
        if (newBase === "GBP") {
            conversionFactor = 1 / valGBP;
        } else if (newBase === "USD") {
            conversionFactor = 1 / valUSD;
        }

        // Recalculate
        valGBP = valGBP * conversionFactor;
        valUSD = valUSD * conversionFactor;

        // Assign back to input elements
        document.getElementById("rate-gbp").value = valGBP.toFixed(6);
        document.getElementById("rate-usd").value = valUSD.toFixed(6);

        ratesConfig.baseCurrency = newBase;
        ratesConfig.rates.GBP = valGBP;
        ratesConfig.rates.USD = valUSD;

        // Disable input for the base currency row
        toggleBaseDisabledState();

        // Mark badge as unsaved/changed
        const badge = document.getElementById("save-badge");
        badge.className = "inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xxs font-bold uppercase tracking-wider bg-yellow-50 dark:bg-yellow-950/20 text-yellow-600 dark:text-yellow-450";
        badge.innerHTML = `<span class="w-1.5 h-1.5 bg-yellow-500 rounded-full animate-ping"></span>Unsaved Changes`;
    }

    // Input Validation
    function validateInput(id) {
        const input = document.getElementById(id);
        const err = document.getElementById("err-" + id);
        const value = input.value.trim();
        const updateBtn = document.getElementById("update-btn");

        // Regexp for positive decimal floats
        const validFloat = /^[0-9]+(\.[0-9]+)?$/;
        
        let isValid = validFloat.test(value) && parseFloat(value) > 0;

        if (isValid) {
            input.classList.remove("border-red-500", "focus:ring-red-500", "focus:border-red-500");
            input.classList.add("focus:ring-[#008060]", "focus:border-[#008060]");
            err.classList.add("hidden");
            updateBtn.disabled = false;
            updateBtn.classList.remove("opacity-50", "cursor-not-allowed");
        } else {
            input.classList.add("border-red-500", "focus:ring-red-500", "focus:border-red-500");
            input.classList.remove("focus:ring-[#008060]", "focus:border-[#008060]");
            err.classList.remove("hidden");
            updateBtn.disabled = true;
            updateBtn.classList.add("opacity-50", "cursor-not-allowed");
        }

        // Change badge status to Unsaved Changes
        const badge = document.getElementById("save-badge");
        badge.className = "inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xxs font-bold uppercase tracking-wider bg-yellow-50 dark:bg-yellow-950/20 text-yellow-600 dark:text-yellow-450";
        badge.innerHTML = `<span class="w-1.5 h-1.5 bg-yellow-500 rounded-full animate-ping"></span>Unsaved Changes`;
        
        return isValid;
    }

    // Cancel / Reset Forms
    function resetRates() {
        loadSavedRates();
        showToast("Configurations reset to last saved state.", "success");
    }

    // Form submission
    function handleUpdate(e) {
        e.preventDefault();

        // Perform validation check on all active currency rates
        const valGBP = parseFloat(document.getElementById("rate-gbp").value) || 0;
        const valUSD = parseFloat(document.getElementById("rate-usd").value) || 0;

        if (valGBP <= 0 || valUSD <= 0) {
            showToast("Invalid conversion rates. Please correct inputs.", "error");
            return;
        }

        // Show updating status
        const updateBtn = document.getElementById("update-btn");
        const btnText = updateBtn.querySelector("span");
        const spinner = document.getElementById("loading-spinner");

        updateBtn.disabled = true;
        updateBtn.classList.add("opacity-80");
        btnText.textContent = "Updating...";
        spinner.classList.remove("hidden");

        setTimeout(() => {
            // Update ratesConfig values
            ratesConfig.rates.GBP = valGBP;
            ratesConfig.rates.USD = valUSD;
            ratesConfig.baseCurrency = document.getElementById("base-currency").value;

            // Save to localStorage
            localStorage.setItem("londontfe_currency_rates", JSON.stringify(ratesConfig));

            // Complete loading action
            updateBtn.disabled = false;
            updateBtn.classList.remove("opacity-80");
            btnText.textContent = "Update";
            spinner.classList.add("hidden");

            // Mark badge as synchronized
            const badge = document.getElementById("save-badge");
            badge.className = "inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xxs font-bold uppercase tracking-wider bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400";
            badge.innerHTML = `<span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>Synchronized`;
            
            // Show premium success toast
            showToast("Currency configuration updated successfully!", "success");
        }, 800);
    }

    // Premium Toast Control
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

        // Slide in from bottom
        toast.classList.remove("translate-y-24", "opacity-0");
        toast.classList.add("translate-y-0", "opacity-100");

        // Slide out after 3.5 seconds
        setTimeout(() => {
            toast.classList.add("translate-y-24", "opacity-0");
            toast.classList.remove("translate-y-0", "opacity-100");
        }, 3500);
    }
</script>

<style>
    .dark .bg-gray-750 {
        background-color: #1e293b;
    }
    .dark .border-gray-650 {
        border-color: #334155;
    }
    .dark .border-gray-755 {
        border-color: #334155;
    }
    .dark .bg-gray-955 {
        background-color: rgba(30, 41, 59, 0.5);
    }
    .text-xxs {
        font-size: 0.7rem;
    }
    .px-4.5 {
        padding-left: 1.125rem;
        padding-right: 1.125rem;
    }
    .py-4.5 {
        padding-top: 1.125rem;
        padding-bottom: 1.125rem;
    }
</style>
@endsection
