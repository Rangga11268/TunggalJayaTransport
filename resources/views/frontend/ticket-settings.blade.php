@extends('frontend.layouts.app')

@section('title', 'Ticket Settings')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Ticket Customization Settings</h1>
    
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form id="ticketSettingsForm">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Layout Type</label>
                    <select id="layoutType" name="layout_type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="landscape">Landscape</option>
                        <option value="portrait">Portrait</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Paper Size</label>
                    <select id="paperSize" name="paper_size" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="A4">A4</option>
                        <option value="A5">A5</option>
                        <option value="letter">Letter</option>
                        <option value="legal">Legal</option>
                        <option value="A3">A3</option>
                    </select>
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" id="showCompanyLogo" name="show_company_logo" class="h-4 w-4 text-blue-600 rounded">
                    <label for="showCompanyLogo" class="ml-2 block text-sm text-gray-700">Show Company Logo</label>
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" id="showBarcode" name="show_barcode" class="h-4 w-4 text-blue-600 rounded">
                    <label for="showBarcode" class="ml-2 block text-sm text-gray-700">Show Barcode</label>
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" id="showQRCode" name="show_qr_code" class="h-4 w-4 text-blue-600 rounded">
                    <label for="showQRCode" class="ml-2 block text-sm text-gray-700">Show QR Code</label>
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" id="enableWatermark" name="enable_watermark" class="h-4 w-4 text-blue-600 rounded">
                    <label for="enableWatermark" class="ml-2 block text-sm text-gray-700">Enable Watermark</label>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Watermark Text</label>
                    <input type="text" id="watermarkText" name="watermark_text" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Primary Color</label>
                    <input type="color" id="primaryColor" name="color_scheme[primary]" value="#1e40af" class="w-full h-10">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Secondary Color</label>
                    <input type="color" id="secondaryColor" name="color_scheme[secondary]" value="#3b82f6" class="w-full h-10">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Accent Color</label>
                    <input type="color" id="accentColor" name="color_scheme[accent]" value="#10b981" class="w-full h-10">
                </div>
            </div>
            
            <div class="mt-6">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">
                    Update Settings
                </button>
            </div>
        </form>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Preview</h2>
        <div class="border border-gray-200 rounded p-4 bg-gray-50">
            <p class="text-gray-600">Ticket preview will appear here based on your settings.</p>
            <p class="text-sm text-gray-500 mt-2">Note: This is a simplified preview. Actual ticket will be generated as per your settings.</p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load current settings
    fetchTicketSettings();
    
    // Handle form submission
    document.getElementById('ticketSettingsForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const settings = Object.fromEntries(formData);
        
        // Convert boolean values
        settings.show_company_logo = settings.show_company_logo === 'on';
        settings.show_barcode = settings.show_barcode === 'on';
        settings.show_qr_code = settings.show_qr_code === 'on';
        settings.enable_watermark = settings.enable_watermark === 'on';
        
        // Prepare color scheme object
        settings.color_scheme = {
            primary: document.getElementById('primaryColor').value,
            secondary: document.getElementById('secondaryColor').value,
            accent: document.getElementById('accentColor').value,
            background: '#ffffff'
        };
        
        fetch("{{ route('ticket.settings.update') }}", {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify(settings)
        })
        .then(response => response.json())
        .then(data => {
            if(data.message) {
                alert('Settings updated successfully!');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating settings.');
        });
    });
    
    function fetchTicketSettings() {
        fetch("{{ route('ticket.settings.get') }}")
        .then(response => response.json())
        .then(data => {
            const settings = data.settings;
            
            if(settings) {
                document.getElementById('layoutType').value = settings.layout_type;
                document.getElementById('paperSize').value = settings.paper_size;
                document.getElementById('showCompanyLogo').checked = settings.show_company_logo;
                document.getElementById('showBarcode').checked = settings.show_barcode;
                document.getElementById('showQRCode').checked = settings.show_qr_code;
                document.getElementById('enableWatermark').checked = settings.enable_watermark;
                
                if(settings.watermark_text) {
                    document.getElementById('watermarkText').value = settings.watermark_text;
                }
                
                if(settings.color_scheme) {
                    document.getElementById('primaryColor').value = settings.color_scheme.primary || '#1e40af';
                    document.getElementById('secondaryColor').value = settings.color_scheme.secondary || '#3b82f6';
                    document.getElementById('accentColor').value = settings.color_scheme.accent || '#10b981';
                }
            }
        })
        .catch(error => {
            console.error('Error fetching settings:', error);
        });
    }
});
</script>
@endsection