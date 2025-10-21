import React, { useState } from 'react';
import { Download, Ticket } from 'lucide-react';

const ETicketPDF = () => {
const [ticketData, setTicketData] = useState({
eventName: 'Summer Music Festival 2025',
eventDate: 'November 15, 2025',
eventTime: '19:00 - 23:00',
venue: 'Grand Arena Stadium',
venueAddress: 'Jl. Sudirman No. 123, Jakarta, Indonesia',
customerName: 'John Doe',
customerEmail: 'john.doe@email.com',
ticketNumber: 'TKT-2025-001234',
seatSection: 'VIP Section A',
seatRow: 'Row 5',
seatNumber: 'Seat 12',
ticketType: 'VIP Admission',
price: 'Rp 750,000',
orderDate: 'October 21, 2025',
orderId: 'ORD-987654321'
});

const generatePDF = async () => {
// Create canvas for PDF generation
const canvas = document.createElement('canvas');
canvas.width = 794; // A4 width in pixels at 96 DPI
canvas.height = 1123; // A4 height in pixels at 96 DPI
const ctx = canvas.getContext('2d');

    // Background
    ctx.fillStyle = '#ffffff';
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    // Header gradient
    const gradient = ctx.createLinearGradient(0, 0, canvas.width, 100);
    gradient.addColorStop(0, '#667eea');
    gradient.addColorStop(1, '#764ba2');
    ctx.fillStyle = gradient;
    ctx.fillRect(40, 40, canvas.width - 80, 80);

    // Header text
    ctx.fillStyle = '#ffffff';
    ctx.font = 'bold 32px Arial';
    ctx.textAlign = 'center';
    ctx.fillText('🎫 E-TICKET', canvas.width / 2, 85);
    ctx.font = '14px Arial';
    ctx.fillText('Official Event Admission Pass', canvas.width / 2, 105);

    // Border
    ctx.strokeStyle = '#667eea';
    ctx.lineWidth = 3;
    ctx.strokeRect(40, 120, canvas.width - 80, canvas.height - 160);

    // Ticket Number Banner
    ctx.fillStyle = '#f7f7f7';
    ctx.fillRect(60, 140, canvas.width - 120, 60);
    ctx.strokeStyle = '#667eea';
    ctx.setLineDash([5, 5]);
    ctx.lineWidth = 2;
    ctx.strokeRect(60, 140, canvas.width - 120, 60);
    ctx.setLineDash([]);

    ctx.fillStyle = '#666666';
    ctx.font = '11px Arial';
    ctx.fillText('TICKET NUMBER', canvas.width / 2, 160);
    ctx.fillStyle = '#667eea';
    ctx.font = 'bold 22px Arial';
    ctx.fillText(ticketData.ticketNumber, canvas.width / 2, 185);

    // Event Name
    ctx.fillStyle = '#333333';
    ctx.font = 'bold 26px Arial';
    ctx.fillText(ticketData.eventName, canvas.width / 2, 235);

    // Line under event name
    ctx.strokeStyle = '#eeeeee';
    ctx.lineWidth = 2;
    ctx.beginPath();
    ctx.moveTo(80, 250);
    ctx.lineTo(canvas.width - 80, 250);
    ctx.stroke();

    // Event Details Grid
    const leftCol = 100;
    const rightCol = canvas.width / 2 + 50;
    let yPos = 290;

    // Left column
    ctx.textAlign = 'left';
    ctx.fillStyle = '#888888';
    ctx.font = '10px Arial';
    ctx.fillText('EVENT DATE', leftCol, yPos);
    ctx.fillStyle = '#333333';
    ctx.font = 'bold 14px Arial';
    ctx.fillText(ticketData.eventDate, leftCol, yPos + 18);

    ctx.fillStyle = '#888888';
    ctx.font = '10px Arial';
    ctx.fillText('TICKET TYPE', leftCol, yPos + 50);
    ctx.fillStyle = '#333333';
    ctx.font = 'bold 14px Arial';
    ctx.fillText(ticketData.ticketType, leftCol, yPos + 68);

    // Right column
    ctx.fillStyle = '#888888';
    ctx.font = '10px Arial';
    ctx.fillText('TIME', rightCol, yPos);
    ctx.fillStyle = '#333333';
    ctx.font = 'bold 14px Arial';
    ctx.fillText(ticketData.eventTime, rightCol, yPos + 18);

    ctx.fillStyle = '#888888';
    ctx.font = '10px Arial';
    ctx.fillText('PRICE', rightCol, yPos + 50);
    ctx.fillStyle = '#333333';
    ctx.font = 'bold 14px Arial';
    ctx.fillText(ticketData.price, rightCol, yPos + 68);

    // Venue Section
    yPos = 410;
    ctx.fillStyle = '#f9f9f9';
    ctx.fillRect(60, yPos, canvas.width - 120, 70);
    ctx.strokeStyle = '#667eea';
    ctx.lineWidth = 4;
    ctx.beginPath();
    ctx.moveTo(60, yPos);
    ctx.lineTo(60, yPos + 70);
    ctx.stroke();

    ctx.fillStyle = '#667eea';
    ctx.font = 'bold 14px Arial';
    ctx.textAlign = 'left';
    ctx.fillText('📍 VENUE INFORMATION', 80, yPos + 25);
    ctx.fillStyle = '#333333';
    ctx.font = 'bold 13px Arial';
    ctx.fillText(ticketData.venue, 80, yPos + 45);
    ctx.font = '12px Arial';
    ctx.fillStyle = '#555555';
    ctx.fillText(ticketData.venueAddress, 80, yPos + 62);

    // Seat Info
    yPos = 500;
    ctx.fillStyle = '#667eea';
    ctx.fillRect(60, yPos, canvas.width - 120, 60);

    ctx.fillStyle = '#ffffff';
    ctx.font = '10px Arial';
    ctx.textAlign = 'center';

    const seatX1 = 200;
    const seatX2 = canvas.width / 2;
    const seatX3 = canvas.width - 200;

    ctx.fillText('SECTION', seatX1, yPos + 20);
    ctx.font = 'bold 18px Arial';
    ctx.fillText(ticketData.seatSection, seatX1, yPos + 42);

    ctx.font = '10px Arial';
    ctx.fillText('ROW', seatX2, yPos + 20);
    ctx.font = 'bold 18px Arial';
    ctx.fillText(ticketData.seatRow, seatX2, yPos + 42);

    ctx.font = '10px Arial';
    ctx.fillText('SEAT', seatX3, yPos + 20);
    ctx.font = 'bold 18px Arial';
    ctx.fillText(ticketData.seatNumber, seatX3, yPos + 42);

    // QR Code and Barcode section background
    yPos = 580;
    ctx.fillStyle = '#f9f9f9';
    ctx.fillRect(60, yPos, canvas.width - 120, 140);

    // QR Code (simple pattern)
    const qrX = 150;
    const qrY = yPos + 20;
    const qrSize = 100;

    ctx.fillStyle = '#ffffff';
    ctx.fillRect(qrX, qrY, qrSize, qrSize);
    ctx.strokeStyle = '#dddddd';
    ctx.lineWidth = 2;
    ctx.strokeRect(qrX, qrY, qrSize, qrSize);

    // QR pattern
    ctx.fillStyle = '#000000';
    for (let i = 0; i < 5; i++) {
      for (let j = 0; j < 5; j++) {
        if ((i + j) % 2 === 0) {
          ctx.fillRect(qrX + 10 + i * 16, qrY + 10 + j * 16, 12, 12);
        }
      }
    }

    ctx.fillStyle = '#666666';
    ctx.font = '10px Arial';
    ctx.textAlign = 'center';
    ctx.fillText('Scan QR Code', qrX + qrSize / 2, qrY + qrSize + 15);

    // Barcode
    const barX = 400;
    const barY = yPos + 30;
    const barWidth = 180;
    const barHeight = 60;

    ctx.fillStyle = '#ffffff';
    ctx.fillRect(barX, barY, barWidth, barHeight);
    ctx.strokeStyle = '#dddddd';
    ctx.lineWidth = 2;
    ctx.strokeRect(barX, barY, barWidth, barHeight);

    // Barcode pattern
    ctx.fillStyle = '#000000';
    for (let i = 0; i < 30; i++) {
      const width = (i % 3 === 0) ? 4 : 2;
      ctx.fillRect(barX + 10 + i * 5, barY + 10, width, 40);
    }

    ctx.fillStyle = '#666666';
    ctx.font = '10px Arial';
    ctx.fillText('Barcode: ' + ticketData.ticketNumber, barX + barWidth / 2, barY + barHeight + 15);

    // Customer Info
    yPos = 740;
    ctx.fillStyle = '#fff5e6';
    ctx.fillRect(60, yPos, canvas.width - 120, 80);
    ctx.strokeStyle = '#ffe0b3';
    ctx.lineWidth = 1;
    ctx.strokeRect(60, yPos, canvas.width - 120, 80);

    ctx.fillStyle = '#ff9800';
    ctx.font = 'bold 14px Arial';
    ctx.textAlign = 'left';
    ctx.fillText('👤 CUSTOMER INFORMATION', 80, yPos + 25);

    ctx.fillStyle = '#888888';
    ctx.font = '11px Arial';
    const custLeftX = 80;
    const custRightX = canvas.width / 2 + 20;

    ctx.fillText('Name', custLeftX, yPos + 45);
    ctx.fillStyle = '#333333';
    ctx.font = 'bold 11px Arial';
    ctx.fillText(ticketData.customerName, custLeftX, yPos + 60);

    ctx.fillStyle = '#888888';
    ctx.font = '11px Arial';
    ctx.fillText('Email', custRightX, yPos + 45);
    ctx.fillStyle = '#333333';
    ctx.font = 'bold 11px Arial';
    ctx.fillText(ticketData.customerEmail, custRightX, yPos + 60);

    // Terms & Conditions
    yPos = 840;
    ctx.strokeStyle = '#dddddd';
    ctx.setLineDash([5, 5]);
    ctx.lineWidth = 2;
    ctx.beginPath();
    ctx.moveTo(60, yPos);
    ctx.lineTo(canvas.width - 60, yPos);
    ctx.stroke();
    ctx.setLineDash([]);

    ctx.fillStyle = '#333333';
    ctx.font = 'bold 12px Arial';
    ctx.textAlign = 'left';
    ctx.fillText('⚠️ TERMS & CONDITIONS', 80, yPos + 25);

    ctx.fillStyle = '#666666';
    ctx.font = '9px Arial';
    const terms = [
      '• This ticket is valid only for the specified date, time, and seat mentioned above.',
      '• Present this e-ticket along with a valid photo ID at the venue entrance.',
      '• This ticket is non-transferable and non-refundable unless the event is cancelled.',
      '• Entry may be refused if the ticket shows signs of tampering or duplication.',
      '• Gates open 1 hour before the event. Please arrive early to avoid queues.'
    ];

    let termY = yPos + 45;
    terms.forEach(term => {
      ctx.fillText(term, 80, termY);
      termY += 15;
    });

    // Footer
    yPos = 1050;
    ctx.strokeStyle = '#eeeeee';
    ctx.lineWidth = 1;
    ctx.setLineDash([]);
    ctx.beginPath();
    ctx.moveTo(60, yPos);
    ctx.lineTo(canvas.width - 60, yPos);
    ctx.stroke();

    ctx.fillStyle = '#888888';
    ctx.font = '10px Arial';
    ctx.textAlign = 'center';
    ctx.fillText('For support: support@eventticket.com | +62-21-1234-5678', canvas.width / 2, yPos + 20);
    ctx.fillText('© 2025 Event Ticket Company. All rights reserved.', canvas.width / 2, yPos + 35);

    // Watermark
    ctx.save();
    ctx.translate(canvas.width / 2, canvas.height / 2);
    ctx.rotate(-45 * Math.PI / 180);
    ctx.fillStyle = 'rgba(102, 126, 234, 0.05)';
    ctx.font = 'bold 60px Arial';
    ctx.textAlign = 'center';
    ctx.fillText('VALID TICKET', 0, 0);
    ctx.restore();

    // Convert canvas to blob and download
    canvas.toBlob((blob) => {
      const url = URL.createObjectURL(blob);
      const link = document.createElement('a');
      link.href = url;
      link.download = `E-Ticket-${ticketData.ticketNumber}.png`;
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
      URL.revokeObjectURL(url);

      // Also trigger print dialog for PDF save
      const printWindow = window.open('', '', 'width=794,height=1123');
      printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
          <title>E-Ticket - ${ticketData.ticketNumber}</title>
          <style>
            @page { size: A4 portrait; margin: 0; }
            body { margin: 0; padding: 0; }
            img { width: 210mm; height: 297mm; display: block; }
          </style>
        </head>
        <body>
          <img src="${canvas.toDataURL()}" />
        </body>
        </html>
      `);
      printWindow.document.close();
      setTimeout(() => printWindow.print(), 500);
    });

};

const handleInputChange = (field, value) => {
setTicketData(prev => ({
...prev,
[field]: value
}));
};

return (
<div className="min-h-screen bg-gradient-to-br from-purple-50 to-blue-50 p-6">
<div className="max-w-4xl mx-auto">
<div className="bg-white rounded-lg shadow-lg p-6 mb-6">
<div className="flex items-center justify-between mb-6">
<div className="flex items-center gap-3">
<Ticket className="w-8 h-8 text-purple-600" />
<h1 className="text-2xl font-bold text-gray-800">E-Ticket PDF Generator</h1>
</div>
<button
              onClick={generatePDF}
              className="flex items-center gap-2 bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition-colors shadow-md"
            >
<Download className="w-5 h-5" />
Download PDF
</button>
</div>

          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Event Name</label>
              <input
                type="text"
                value={ticketData.eventName}
                onChange={(e) => handleInputChange('eventName', e.target.value)}
                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Event Date</label>
              <input
                type="text"
                value={ticketData.eventDate}
                onChange={(e) => handleInputChange('eventDate', e.target.value)}
                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Event Time</label>
              <input
                type="text"
                value={ticketData.eventTime}
                onChange={(e) => handleInputChange('eventTime', e.target.value)}
                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Venue</label>
              <input
                type="text"
                value={ticketData.venue}
                onChange={(e) => handleInputChange('venue', e.target.value)}
                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
              />
            </div>

            <div className="md:col-span-2">
              <label className="block text-sm font-medium text-gray-700 mb-1">Venue Address</label>
              <input
                type="text"
                value={ticketData.venueAddress}
                onChange={(e) => handleInputChange('venueAddress', e.target.value)}
                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Customer Name</label>
              <input
                type="text"
                value={ticketData.customerName}
                onChange={(e) => handleInputChange('customerName', e.target.value)}
                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Customer Email</label>
              <input
                type="email"
                value={ticketData.customerEmail}
                onChange={(e) => handleInputChange('customerEmail', e.target.value)}
                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Ticket Number</label>
              <input
                type="text"
                value={ticketData.ticketNumber}
                onChange={(e) => handleInputChange('ticketNumber', e.target.value)}
                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Ticket Type</label>
              <input
                type="text"
                value={ticketData.ticketType}
                onChange={(e) => handleInputChange('ticketType', e.target.value)}
                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Seat Section</label>
              <input
                type="text"
                value={ticketData.seatSection}
                onChange={(e) => handleInputChange('seatSection', e.target.value)}
                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Seat Row</label>
              <input
                type="text"
                value={ticketData.seatRow}
                onChange={(e) => handleInputChange('seatRow', e.target.value)}
                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Seat Number</label>
              <input
                type="text"
                value={ticketData.seatNumber}
                onChange={(e) => handleInputChange('seatNumber', e.target.value)}
                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Price</label>
              <input
                type="text"
                value={ticketData.price}
                onChange={(e) => handleInputChange('price', e.target.value)}
                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Order Date</label>
              <input
                type="text"
                value={ticketData.orderDate}
                onChange={(e) => handleInputChange('orderDate', e.target.value)}
                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Order ID</label>
              <input
                type="text"
                value={ticketData.orderId}
                onChange={(e) => handleInputChange('orderId', e.target.value)}
                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
              />
            </div>
          </div>
        </div>

        <div className="bg-blue-50 border border-blue-200 rounded-lg p-4">
          <h3 className="font-semibold text-blue-800 mb-2">📋 How to use:</h3>
          <ol className="text-sm text-blue-700 space-y-1 list-decimal list-inside">
            <li>Fill in all the ticket information in the form above</li>
            <li>Click "Download PDF" button</li>
            <li>A print dialog will automatically open</li>
            <li>Select "Save as PDF" or "Microsoft Print to PDF" as printer</li>
            <li>Click Save and choose your location</li>
            <li>Your A4 portrait ticket PDF will be saved!</li>
          </ol>
        </div>
      </div>
    </div>

);
};

export default ETicketPDF;

oke saya ingin anda lihat @updatePDF.md nah ini adalah code dalam bentuk react sepertinya atau javascrip yang berfungsi untuk mengenerate tiket nah saya ingin yang pertama ini kan jadi nya image jadiin pdf dan yang kedua jangan gunakan react gunakan html dan yang terakhir saya ingin ambil sistem saat di pencet download pdfnya aja sisanya tidak dan tentu sesuaikan dengan project kita





VERSI 2
import React, { useState } from 'react';
import { Download, Ticket } from 'lucide-react';

const ETicketPDF = () => {
  const [ticketData, setTicketData] = useState({
    eventName: 'Summer Music Festival 2025',
    eventDate: 'November 15, 2025',
    eventTime: '19:00 - 23:00',
    venue: 'Grand Arena Stadium',
    venueAddress: 'Jl. Sudirman No. 123, Jakarta, Indonesia',
    customerName: 'John Doe',
    customerEmail: 'john.doe@email.com',
    ticketNumber: 'TKT-2025-001234',
    seatSection: 'VIP Section A',
    seatRow: 'Row 5',
    seatNumber: 'Seat 12',
    ticketType: 'VIP Admission',
    price: 'Rp 750,000',
    orderDate: 'October 21, 2025',
    orderId: 'ORD-987654321'
  });

  const generatePDF = () => {
    const htmlContent = `
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>E-Ticket - ${ticketData.ticketNumber}</title>
  <style>
    @page {
      size: A4 portrait;
      margin: 0;
    }
    
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body {
      font-family: Arial, sans-serif;
      width: 210mm;
      height: 297mm;
      margin: 0;
      padding: 0;
      background: white;
    }
    
    .ticket-container {
      width: 100%;
      height: 100%;
      padding: 15mm;
      position: relative;
    }
    
    .watermark {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%) rotate(-45deg);
      font-size: 72px;
      color: rgba(102, 126, 234, 0.05);
      font-weight: bold;
      pointer-events: none;
      z-index: 1;
    }
    
    .content {
      position: relative;
      z-index: 2;
    }
    
    .header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 25px;
      border-radius: 12px 12px 0 0;
      text-align: center;
    }
    
    .header h1 {
      font-size: 32px;
      margin-bottom: 8px;
      font-weight: bold;
    }
    
    .header .subtitle {
      font-size: 15px;
      opacity: 0.95;
    }
    
    .ticket-body {
      border: 3px solid #667eea;
      border-top: none;
      border-radius: 0 0 12px 12px;
      padding: 25px;
      min-height: 880px;
    }
    
    .ticket-number-banner {
      background: #f7f7f7;
      padding: 15px;
      text-align: center;
      border-radius: 8px;
      margin-bottom: 25px;
      border: 2px dashed #667eea;
    }
    
    .ticket-number-banner .label {
      font-size: 11px;
      color: #666;
      margin-bottom: 5px;
      letter-spacing: 1px;
    }
    
    .ticket-number-banner .number {
      font-size: 24px;
      font-weight: bold;
      color: #667eea;
      letter-spacing: 3px;
    }
    
    .event-name {
      font-size: 28px;
      font-weight: bold;
      color: #333;
      margin-bottom: 20px;
      text-align: center;
      padding-bottom: 20px;
      border-bottom: 2px solid #eee;
    }
    
    .detail-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
      margin-bottom: 25px;
    }
    
    .detail-item {
      padding: 12px;
      background: #fafafa;
      border-radius: 6px;
    }
    
    .detail-item .label {
      font-size: 11px;
      color: #888;
      text-transform: uppercase;
      margin-bottom: 5px;
      letter-spacing: 0.5px;
    }
    
    .detail-item .value {
      font-size: 15px;
      color: #333;
      font-weight: 600;
    }
    
    .venue-section {
      background: #f9f9f9;
      padding: 18px;
      border-radius: 8px;
      margin-bottom: 25px;
      border-left: 5px solid #667eea;
    }
    
    .venue-section h3 {
      font-size: 15px;
      color: #667eea;
      margin-bottom: 10px;
      font-weight: bold;
    }
    
    .venue-section .venue-name {
      font-size: 15px;
      color: #333;
      font-weight: bold;
      margin-bottom: 5px;
    }
    
    .venue-section .venue-addr {
      font-size: 13px;
      color: #555;
      line-height: 1.6;
    }
    
    .seat-info {
      display: flex;
      justify-content: space-around;
      background: #667eea;
      color: white;
      padding: 20px;
      border-radius: 8px;
      margin-bottom: 25px;
    }
    
    .seat-detail {
      text-align: center;
    }
    
    .seat-detail .label {
      font-size: 11px;
      opacity: 0.85;
      margin-bottom: 8px;
      letter-spacing: 1px;
    }
    
    .seat-detail .value {
      font-size: 18px;
      font-weight: bold;
    }
    
    .qr-barcode-section {
      display: flex;
      justify-content: space-around;
      align-items: center;
      margin: 25px 0;
      padding: 25px;
      background: #f9f9f9;
      border-radius: 8px;
    }
    
    .qr-code, .barcode {
      text-align: center;
    }
    
    .qr-code-box {
      width: 130px;
      height: 130px;
      background: white;
      border: 2px solid #ddd;
      margin: 0 auto 10px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    .barcode-box {
      width: 220px;
      height: 90px;
      background: white;
      border: 2px solid #ddd;
      margin: 0 auto 10px;
      border-radius: 5px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    .code-label {
      font-size: 11px;
      color: #666;
    }
    
    .customer-info {
      background: #fff5e6;
      padding: 18px;
      border-radius: 8px;
      margin-bottom: 20px;
      border: 1px solid #ffe0b3;
    }
    
    .customer-info h3 {
      font-size: 15px;
      color: #ff9800;
      margin-bottom: 12px;
      font-weight: bold;
    }
    
    .customer-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
    }
    
    .customer-item .label {
      font-size: 11px;
      color: #888;
      margin-bottom: 3px;
    }
    
    .customer-item .value {
      font-size: 13px;
      color: #333;
      font-weight: 600;
    }
    
    .terms {
      padding-top: 20px;
      border-top: 2px dashed #ddd;
      margin-top: 20px;
    }
    
    .terms h3 {
      font-size: 13px;
      color: #333;
      margin-bottom: 12px;
      font-weight: bold;
    }
    
    .terms ul {
      list-style: none;
      padding: 0;
    }
    
    .terms li {
      font-size: 10px;
      color: #666;
      margin-bottom: 6px;
      padding-left: 18px;
      position: relative;
      line-height: 1.5;
    }
    
    .terms li:before {
      content: "•";
      position: absolute;
      left: 5px;
      color: #667eea;
      font-weight: bold;
    }
    
    .footer {
      text-align: center;
      margin-top: 20px;
      padding-top: 15px;
      border-top: 1px solid #eee;
    }
    
    .footer p {
      font-size: 10px;
      color: #888;
      margin: 3px 0;
    }
    
    svg {
      width: 100%;
      height: 100%;
    }
    
    @media print {
      body {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        color-adjust: exact !important;
      }
      
      .header {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
      }
      
      .seat-info {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
      }
    }
  </style>
</head>
<body>
  <div class="ticket-container">
    <div class="watermark">VALID TICKET</div>
    
    <div class="content">
      <div class="header">
        <h1>🎫 E-TICKET</h1>
        <p class="subtitle">Official Event Admission Pass</p>
      </div>
      
      <div class="ticket-body">
        <div class="ticket-number-banner">
          <div class="label">TICKET NUMBER</div>
          <div class="number">${ticketData.ticketNumber}</div>
        </div>
        
        <div class="event-name">${ticketData.eventName}</div>
        
        <div class="detail-grid">
          <div class="detail-item">
            <div class="label">📅 Event Date</div>
            <div class="value">${ticketData.eventDate}</div>
          </div>
          
          <div class="detail-item">
            <div class="label">⏰ Time</div>
            <div class="value">${ticketData.eventTime}</div>
          </div>
          
          <div class="detail-item">
            <div class="label">🎟️ Ticket Type</div>
            <div class="value">${ticketData.ticketType}</div>
          </div>
          
          <div class="detail-item">
            <div class="label">💰 Price</div>
            <div class="value">${ticketData.price}</div>
          </div>
        </div>
        
        <div class="venue-section">
          <h3>📍 VENUE INFORMATION</h3>
          <div class="venue-name">${ticketData.venue}</div>
          <div class="venue-addr">${ticketData.venueAddress}</div>
        </div>
        
        <div class="seat-info">
          <div class="seat-detail">
            <div class="label">SECTION</div>
            <div class="value">${ticketData.seatSection}</div>
          </div>
          <div class="seat-detail">
            <div class="label">ROW</div>
            <div class="value">${ticketData.seatRow}</div>
          </div>
          <div class="seat-detail">
            <div class="label">SEAT</div>
            <div class="value">${ticketData.seatNumber}</div>
          </div>
        </div>
        
        <div class="qr-barcode-section">
          <div class="qr-code">
            <div class="qr-code-box">
              <svg viewBox="0 0 100 100" width="110" height="110">
                <rect width="100" height="100" fill="white"/>
                <rect x="10" y="10" width="15" height="15" fill="black"/>
                <rect x="30" y="10" width="10" height="15" fill="black"/>
                <rect x="50" y="10" width="15" height="15" fill="black"/>
                <rect x="75" y="10" width="15" height="15" fill="black"/>
                <rect x="10" y="30" width="10" height="10" fill="black"/>
                <rect x="25" y="30" width="15" height="10" fill="black"/>
                <rect x="50" y="30" width="10" height="10" fill="black"/>
                <rect x="70" y="30" width="10" height="10" fill="black"/>
                <rect x="85" y="30" width="5" height="10" fill="black"/>
                <rect x="10" y="45" width="15" height="10" fill="black"/>
                <rect x="35" y="45" width="10" height="10" fill="black"/>
                <rect x="55" y="45" width="15" height="10" fill="black"/>
                <rect x="75" y="45" width="10" height="10" fill="black"/>
                <rect x="10" y="60" width="10" height="15" fill="black"/>
                <rect x="30" y="60" width="15" height="10" fill="black"/>
                <rect x="50" y="60" width="10" height="15" fill="black"/>
                <rect x="70" y="60" width="15" height="10" fill="black"/>
                <rect x="10" y="80" width="15" height="10" fill="black"/>
                <rect x="35" y="80" width="10" height="10" fill="black"/>
                <rect x="55" y="80" width="10" height="10" fill="black"/>
                <rect x="75" y="80" width="15" height="10" fill="black"/>
              </svg>
            </div>
            <div class="code-label">Scan QR Code</div>
          </div>
          
          <div class="barcode">
            <div class="barcode-box">
              <svg viewBox="0 0 200 60" width="200" height="70">
                <rect x="10" y="10" width="3" height="40" fill="black"/>
                <rect x="15" y="10" width="2" height="40" fill="black"/>
                <rect x="20" y="10" width="4" height="40" fill="black"/>
                <rect x="27" y="10" width="2" height="40" fill="black"/>
                <rect x="32" y="10" width="5" height="40" fill="black"/>
                <rect x="40" y="10" width="2" height="40" fill="black"/>
                <rect x="45" y="10" width="3" height="40" fill="black"/>
                <rect x="51" y="10" width="4" height="40" fill="black"/>
                <rect x="58" y="10" width="2" height="40" fill="black"/>
                <rect x="63" y="10" width="3" height="40" fill="black"/>
                <rect x="69" y="10" width="5" height="40" fill="black"/>
                <rect x="77" y="10" width="2" height="40" fill="black"/>
                <rect x="82" y="10" width="4" height="40" fill="black"/>
                <rect x="89" y="10" width="3" height="40" fill="black"/>
                <rect x="95" y="10" width="2" height="40" fill="black"/>
                <rect x="100" y="10" width="5" height="40" fill="black"/>
                <rect x="108" y="10" width="2" height="40" fill="black"/>
                <rect x="113" y="10" width="4" height="40" fill="black"/>
                <rect x="120" y="10" width="3" height="40" fill="black"/>
                <rect x="126" y="10" width="2" height="40" fill="black"/>
                <rect x="131" y="10" width="5" height="40" fill="black"/>
                <rect x="139" y="10" width="3" height="40" fill="black"/>
                <rect x="145" y="10" width="2" height="40" fill="black"/>
                <rect x="150" y="10" width="4" height="40" fill="black"/>
                <rect x="157" y="10" width="2" height="40" fill="black"/>
                <rect x="162" y="10" width="5" height="40" fill="black"/>
                <rect x="170" y="10" width="3" height="40" fill="black"/>
                <rect x="176" y="10" width="2" height="40" fill="black"/>
                <rect x="181" y="10" width="4" height="40" fill="black"/>
              </svg>
            </div>
            <div class="code-label">Barcode: ${ticketData.ticketNumber}</div>
          </div>
        </div>
        
        <div class="customer-info">
          <h3>👤 CUSTOMER INFORMATION</h3>
          <div class="customer-grid">
            <div class="customer-item">
              <div class="label">Name</div>
              <div class="value">${ticketData.customerName}</div>
            </div>
            <div class="customer-item">
              <div class="label">Email</div>
              <div class="value">${ticketData.customerEmail}</div>
            </div>
            <div class="customer-item">
              <div class="label">Order Date</div>
              <div class="value">${ticketData.orderDate}</div>
            </div>
            <div class="customer-item">
              <div class="label">Order ID</div>
              <div class="value">${ticketData.orderId}</div>
            </div>
          </div>
        </div>
        
        <div class="terms">
          <h3>⚠️ TERMS & CONDITIONS</h3>
          <ul>
            <li>This ticket is valid only for the specified date, time, and seat mentioned above.</li>
            <li>Present this e-ticket (printed or digital) along with a valid photo ID at the venue entrance.</li>
            <li>This ticket is non-transferable and non-refundable unless the event is cancelled.</li>
            <li>Entry may be refused if the ticket shows signs of tampering or duplication.</li>
            <li>Photography, video recording, and audio recording may be prohibited during the event.</li>
            <li>The organizer reserves the right to add, withdraw, or substitute artists and/or vary the program.</li>
            <li>Gates open 1 hour before the event start time. Please arrive early to avoid queues.</li>
            <li>Prohibited items include weapons, illegal substances, outside food/beverages, and professional cameras.</li>
          </ul>
        </div>
        
        <div class="footer">
          <p>For support and inquiries: support@eventticket.com | +62-21-1234-5678</p>
          <p>© 2025 Event Ticket Company. All rights reserved.</p>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
    `;

    // Create a new window with the content
    const printWindow = window.open('', '_blank', 'width=800,height=600');
    printWindow.document.write(htmlContent);
    printWindow.document.close();
    
    // Wait for content to load, then trigger print
    printWindow.onload = function() {
      setTimeout(function() {
        printWindow.focus();
        printWindow.print();
      }, 500);
    };
  };

  const handleInputChange = (field, value) => {
    setTicketData(prev => ({
      ...prev,
      [field]: value
    }));
  };

  return (
    <div className="min-h-screen bg-gradient-to-br from-purple-50 to-blue-50 p-6">
      <div className="max-w-4xl mx-auto">
        <div className="bg-white rounded-lg shadow-lg p-6 mb-6">
          <div className="flex items-center justify-between mb-6">
            <div className="flex items-center gap-3">
              <Ticket className="w-8 h-8 text-purple-600" />
              <h1 className="text-2xl font-bold text-gray-800">E-Ticket PDF Generator</h1>
            </div>
            <button
              onClick={generatePDF}
              className="flex items-center gap-2 bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition-colors shadow-md"
            >
              <Download className="w-5 h-5" />
              Generate PDF
            </button>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Event Name</label>
              <input
                type="text"
                value={ticketData.eventName}
                onChange={(e) => handleInputChange('eventName', e.target.value)}
                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Event Date</label>
              <input
                type="text"
                value={ticketData.eventDate}
                onChange={(e) => handleInputChange('eventDate', e.target.value)}
                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Event Time</label>
              <input
                type="text"
                value={ticketData.eventTime}
                onChange={(e) => handleInputChange('eventTime', e.target.value)}
                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Venue</label>
              <input
                type="text"
                value={ticketData.venue}
                onChange={(e) => handleInputChange('venue', e.target.value)}
                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
              />
            </div>

            <div className="md:col-span-2">
              <label className="block text-sm font-medium text-gray-700 mb-1">Venue Address</label>
              <input
                type="text"
                value={ticketData.venueAddress}
                onChange={(e) => handleInputChange('venueAddress', e.target.value)}
                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Customer Name</label>
              <input
                type="text"
                value={ticketData.customerName}
                onChange={(e) => handleInputChange('customerName', e.target.value)}
                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Customer Email</label>
              <input
                type="email"
                value={ticketData.customerEmail}
                onChange={(e) => handleInputChange('customerEmail', e.target.value)}
                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Ticket Number</label>
              <input
                type="text"
                value={ticketData.ticketNumber}
                onChange={(e) => handleInputChange('ticketNumber', e.target.value)}
                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Ticket Type</label>
              <input
                type="text"
                value={ticketData.ticketType}
                onChange={(e) => handleInputChange('ticketType', e.target.value)}
                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Seat Section</label>
              <input
                type="text"
                value={ticketData.seatSection}
                onChange={(e) => handleInputChange('seatSection', e.target.value)}
                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Seat Row</label>
              <input
                type="text"
                value={ticketData.seatRow}
                onChange={(e) => handleInputChange('seatRow', e.target.value)}
                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Seat Number</label>
              <input
                type="text"
                value={ticketData.seatNumber}
                onChange={(e) => handleInputChange('seatNumber', e.target.value)}
                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Price</label>
              <input
                type="text"
                value={ticketData.price}
                onChange={(e) => handleInputChange('price', e.target.value)}
                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Order Date</label>
              <input
                type="text"
                value={ticketData.orderDate}
                onChange={(e) => handleInputChange('orderDate', e.target.value)}
                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Order ID</label>
              <input
                type="text"
                value={ticketData.orderId}
                onChange={(e) => handleInputChange('orderId', e.target.value)}
                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
              />
            </div>
          </div>
        </div>

        <div className="bg-blue-50 border border-blue-200 rounded-lg p-4">
          <h3 className="font-semibold text-blue-800 mb-2">📋 How to Save as PDF:</h3>
          <ol className="text-sm text-blue-700 space-y-1 list-decimal list-inside">
            <li>Click the "Generate PDF" button above</li>
            <li>A new window will open with your ticket</li>
            <li>In the print dialog that appears automatically:</li>
            <li className="ml-6">• Select "Save as PDF" or "Microsoft Print to PDF" as the destination/printer</li>
            <li className="ml-6">• Make sure paper size is set to A4</li>
            <li className="ml-6">• Orientation should be Portrait</li>
            <li>Click "Save" and choose where to save your PDF file</li>
            <li>Your ticket PDF is now ready to use!</li>
          </ol>
        </div>
      </div>
    </div>
  );
};

export default ETicketPDF;




========================================================================================


Versi laravel 

<?php

// STEP 1: Install the package
// Run this command in your Laravel project:
// composer require barryvdh/laravel-dompdf

// STEP 2: Create Controller
// app/Http/Controllers/TicketController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class TicketController extends Controller
{
    public function downloadTicket(Request $request)
    {
        // Get ticket data from request or database
        $ticketData = [
            'eventName' => $request->input('eventName', 'Summer Music Festival 2025'),
            'eventDate' => $request->input('eventDate', 'November 15, 2025'),
            'eventTime' => $request->input('eventTime', '19:00 - 23:00'),
            'venue' => $request->input('venue', 'Grand Arena Stadium'),
            'venueAddress' => $request->input('venueAddress', 'Jl. Sudirman No. 123, Jakarta, Indonesia'),
            'customerName' => $request->input('customerName', 'John Doe'),
            'customerEmail' => $request->input('customerEmail', 'john.doe@email.com'),
            'ticketNumber' => $request->input('ticketNumber', 'TKT-2025-001234'),
            'seatSection' => $request->input('seatSection', 'VIP Section A'),
            'seatRow' => $request->input('seatRow', 'Row 5'),
            'seatNumber' => $request->input('seatNumber', 'Seat 12'),
            'ticketType' => $request->input('ticketType', 'VIP Admission'),
            'price' => $request->input('price', 'Rp 750,000'),
            'orderDate' => $request->input('orderDate', 'October 21, 2025'),
            'orderId' => $request->input('orderId', 'ORD-987654321'),
        ];

        // Load the view and pass data
        $pdf = Pdf::loadView('tickets.eticket', $ticketData);
        
        // Set paper size to A4 portrait
        $pdf->setPaper('A4', 'portrait');
        
        // Download the PDF
        return $pdf->download('E-Ticket-' . $ticketData['ticketNumber'] . '.pdf');
    }
    
    public function showTicketForm()
    {
        return view('tickets.form');
    }
}

// STEP 3: Create Route
// routes/web.php
/*
use App\Http\Controllers\TicketController;

Route::get('/ticket/form', [TicketController::class, 'showTicketForm'])->name('ticket.form');
Route::post('/ticket/download', [TicketController::class, 'downloadTicket'])->name('ticket.download');
*/

// STEP 4: Create Blade View for the PDF
// resources/views/tickets/eticket.blade.php
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>E-Ticket - {{ $ticketNumber }}</title>
    <style>
        @page {
            margin: 0;
            size: A4 portrait;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            width: 210mm;
            height: 297mm;
            margin: 0;
            padding: 15mm;
            background: white;
        }
        
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 72px;
            color: rgba(102, 126, 234, 0.05);
            font-weight: bold;
            z-index: 1;
        }
        
        .content {
            position: relative;
            z-index: 2;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 12px 12px 0 0;
            text-align: center;
        }
        
        .header h1 {
            font-size: 32px;
            margin-bottom: 8px;
            font-weight: bold;
        }
        
        .header .subtitle {
            font-size: 15px;
        }
        
        .ticket-body {
            border: 3px solid #667eea;
            border-top: none;
            border-radius: 0 0 12px 12px;
            padding: 25px;
        }
        
        .ticket-number-banner {
            background: #f7f7f7;
            padding: 15px;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 25px;
            border: 2px dashed #667eea;
        }
        
        .ticket-number-banner .label {
            font-size: 11px;
            color: #666;
            margin-bottom: 5px;
            letter-spacing: 1px;
        }
        
        .ticket-number-banner .number {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
            letter-spacing: 3px;
        }
        
        .event-name {
            font-size: 28px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #eee;
        }
        
        .detail-grid {
            width: 100%;
            margin-bottom: 25px;
        }
        
        .detail-row {
            width: 100%;
            margin-bottom: 10px;
        }
        
        .detail-item {
            display: inline-block;
            width: 48%;
            padding: 12px;
            background: #fafafa;
            border-radius: 6px;
            vertical-align: top;
        }
        
        .detail-item:nth-child(2n) {
            margin-left: 3%;
        }
        
        .detail-item .label {
            font-size: 11px;
            color: #888;
            text-transform: uppercase;
            margin-bottom: 5px;
            letter-spacing: 0.5px;
        }
        
        .detail-item .value {
            font-size: 15px;
            color: #333;
            font-weight: 600;
        }
        
        .venue-section {
            background: #f9f9f9;
            padding: 18px;
            border-radius: 8px;
            margin-bottom: 25px;
            border-left: 5px solid #667eea;
        }
        
        .venue-section h3 {
            font-size: 15px;
            color: #667eea;
            margin-bottom: 10px;
            font-weight: bold;
        }
        
        .venue-section .venue-name {
            font-size: 15px;
            color: #333;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .venue-section .venue-addr {
            font-size: 13px;
            color: #555;
            line-height: 1.6;
        }
        
        .seat-info {
            background: #667eea;
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            text-align: center;
        }
        
        .seat-detail {
            display: inline-block;
            width: 32%;
            text-align: center;
        }
        
        .seat-detail .label {
            font-size: 11px;
            margin-bottom: 8px;
            letter-spacing: 1px;
        }
        
        .seat-detail .value {
            font-size: 18px;
            font-weight: bold;
        }
        
        .qr-barcode-section {
            background: #f9f9f9;
            padding: 25px;
            border-radius: 8px;
            margin-bottom: 25px;
            text-align: center;
        }
        
        .qr-code {
            display: inline-block;
            width: 45%;
            text-align: center;
            vertical-align: top;
        }
        
        .barcode {
            display: inline-block;
            width: 45%;
            text-align: center;
            vertical-align: top;
            margin-left: 8%;
        }
        
        .qr-code-box {
            width: 130px;
            height: 130px;
            background: white;
            border: 2px solid #ddd;
            margin: 0 auto 10px;
            border-radius: 8px;
        }
        
        .barcode-box {
            width: 220px;
            height: 90px;
            background: white;
            border: 2px solid #ddd;
            margin: 0 auto 10px;
            border-radius: 5px;
        }
        
        .code-label {
            font-size: 11px;
            color: #666;
        }
        
        .customer-info {
            background: #fff5e6;
            padding: 18px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #ffe0b3;
        }
        
        .customer-info h3 {
            font-size: 15px;
            color: #ff9800;
            margin-bottom: 12px;
            font-weight: bold;
        }
        
        .customer-item {
            display: inline-block;
            width: 48%;
            margin-bottom: 10px;
            vertical-align: top;
        }
        
        .customer-item:nth-child(2n+1) {
            margin-right: 3%;
        }
        
        .customer-item .label {
            font-size: 11px;
            color: #888;
            margin-bottom: 3px;
        }
        
        .customer-item .value {
            font-size: 13px;
            color: #333;
            font-weight: 600;
        }
        
        .terms {
            padding-top: 20px;
            border-top: 2px dashed #ddd;
            margin-top: 20px;
        }
        
        .terms h3 {
            font-size: 13px;
            color: #333;
            margin-bottom: 12px;
            font-weight: bold;
        }
        
        .terms ul {
            list-style: none;
            padding: 0;
        }
        
        .terms li {
            font-size: 10px;
            color: #666;
            margin-bottom: 6px;
            padding-left: 18px;
            position: relative;
            line-height: 1.5;
        }
        
        .terms li:before {
            content: "•";
            position: absolute;
            left: 5px;
            color: #667eea;
            font-weight: bold;
        }
        
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }
        
        .footer p {
            font-size: 10px;
            color: #888;
            margin: 3px 0;
        }
        
        svg {
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>
    <div class="watermark">VALID TICKET</div>
    
    <div class="content">
        <div class="header">
            <h1>🎫 E-TICKET</h1>
            <p class="subtitle">Official Event Admission Pass</p>
        </div>
        
        <div class="ticket-body">
            <div class="ticket-number-banner">
                <div class="label">TICKET NUMBER</div>
                <div class="number">{{ $ticketNumber }}</div>
            </div>
            
            <div class="event-name">{{ $eventName }}</div>
            
            <div class="detail-grid">
                <div class="detail-row">
                    <div class="detail-item">
                        <div class="label">📅 Event Date</div>
                        <div class="value">{{ $eventDate }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="label">⏰ Time</div>
                        <div class="value">{{ $eventTime }}</div>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-item">
                        <div class="label">🎟️ Ticket Type</div>
                        <div class="value">{{ $ticketType }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="label">💰 Price</div>
                        <div class="value">{{ $price }}</div>
                    </div>
                </div>
            </div>
            
            <div class="venue-section">
                <h3>📍 VENUE INFORMATION</h3>
                <div class="venue-name">{{ $venue }}</div>
                <div class="venue-addr">{{ $venueAddress }}</div>
            </div>
            
            <div class="seat-info">
                <div class="seat-detail">
                    <div class="label">SECTION</div>
                    <div class="value">{{ $seatSection }}</div>
                </div>
                <div class="seat-detail">
                    <div class="label">ROW</div>
                    <div class="value">{{ $seatRow }}</div>
                </div>
                <div class="seat-detail">
                    <div class="label">SEAT</div>
                    <div class="value">{{ $seatNumber }}</div>
                </div>
            </div>
            
            <div class="qr-barcode-section">
                <div class="qr-code">
                    <div class="qr-code-box">
                        <svg viewBox="0 0 100 100" width="110" height="110">
                            <rect width="100" height="100" fill="white"/>
                            <rect x="10" y="10" width="15" height="15" fill="black"/>
                            <rect x="30" y="10" width="10" height="15" fill="black"/>
                            <rect x="50" y="10" width="15" height="15" fill="black"/>
                            <rect x="75" y="10" width="15" height="15" fill="black"/>
                            <rect x="10" y="30" width="10" height="10" fill="black"/>
                            <rect x="25" y="30" width="15" height="10" fill="black"/>
                            <rect x="50" y="30" width="10" height="10" fill="black"/>
                            <rect x="70" y="30" width="10" height="10" fill="black"/>
                            <rect x="85" y="30" width="5" height="10" fill="black"/>
                            <rect x="10" y="45" width="15" height="10" fill="black"/>
                            <rect x="35" y="45" width="10" height="10" fill="black"/>
                            <rect x="55" y="45" width="15" height="10" fill="black"/>
                            <rect x="75" y="45" width="10" height="10" fill="black"/>
                            <rect x="10" y="60" width="10" height="15" fill="black"/>
                            <rect x="30" y="60" width="15" height="10" fill="black"/>
                            <rect x="50" y="60" width="10" height="15" fill="black"/>
                            <rect x="70" y="60" width="15" height="10" fill="black"/>
                            <rect x="10" y="80" width="15" height="10" fill="black"/>
                            <rect x="35" y="80" width="10" height="10" fill="black"/>
                            <rect x="55" y="80" width="10" height="10" fill="black"/>
                            <rect x="75" y="80" width="15" height="10" fill="black"/>
                        </svg>
                    </div>
                    <div class="code-label">Scan QR Code</div>
                </div>
                
                <div class="barcode">
                    <div class="barcode-box">
                        <svg viewBox="0 0 200 60" width="200" height="70">
                            <rect x="10" y="10" width="3" height="40" fill="black"/>
                            <rect x="15" y="10" width="2" height="40" fill="black"/>
                            <rect x="20" y="10" width="4" height="40" fill="black"/>
                            <rect x="27" y="10" width="2" height="40" fill="black"/>
                            <rect x="32" y="10" width="5" height="40" fill="black"/>
                            <rect x="40" y="10" width="2" height="40" fill="black"/>
                            <rect x="45" y="10" width="3" height="40" fill="black"/>
                            <rect x="51" y="10" width="4" height="40" fill="black"/>
                            <rect x="58" y="10" width="2" height="40" fill="black"/>
                            <rect x="63" y="10" width="3" height="40" fill="black"/>
                            <rect x="69" y="10" width="5" height="40" fill="black"/>
                            <rect x="77" y="10" width="2" height="40" fill="black"/>
                            <rect x="82" y="10" width="4" height="40" fill="black"/>
                            <rect x="89" y="10" width="3" height="40" fill="black"/>
                            <rect x="95" y="10" width="2" height="40" fill="black"/>
                            <rect x="100" y="10" width="5" height="40" fill="black"/>
                            <rect x="108" y="10" width="2" height="40" fill="black"/>
                            <rect x="113" y="10" width="4" height="40" fill="black"/>
                            <rect x="120" y="10" width="3" height="40" fill="black"/>
                            <rect x="126" y="10" width="2" height="40" fill="black"/>
                            <rect x="131" y="10" width="5" height="40" fill="black"/>
                            <rect x="139" y="10" width="3" height="40" fill="black"/>
                            <rect x="145" y="10" width="2" height="40" fill="black"/>
                            <rect x="150" y="10" width="4" height="40" fill="black"/>
                            <rect x="157" y="10" width="2" height="40" fill="black"/>
                            <rect x="162" y="10" width="5" height="40" fill="black"/>
                            <rect x="170" y="10" width="3" height="40" fill="black"/>
                            <rect x="176" y="10" width="2" height="40" fill="black"/>
                            <rect x="181" y="10" width="4" height="40" fill="black"/>
                        </svg>
                    </div>
                    <div class="code-label">Barcode: {{ $ticketNumber }}</div>
                </div>
            </div>
            
            <div class="customer-info">
                <h3>👤 CUSTOMER INFORMATION</h3>
                <div>
                    <div class="customer-item">
                        <div class="label">Name</div>
                        <div class="value">{{ $customerName }}</div>
                    </div>
                    <div class="customer-item">
                        <div class="label">Email</div>
                        <div class="value">{{ $customerEmail }}</div>
                    </div>
                    <div class="customer-item">
                        <div class="label">Order Date</div>
                        <div class="value">{{ $orderDate }}</div>
                    </div>
                    <div class="customer-item">
                        <div class="label">Order ID</div>
                        <div class="value">{{ $orderId }}</div>
                    </div>
                </div>
            </div>
            
            <div class="terms">
                <h3>⚠️ TERMS & CONDITIONS</h3>
                <ul>
                    <li>This ticket is valid only for the specified date, time, and seat mentioned above.</li>
                    <li>Present this e-ticket (printed or digital) along with a valid photo ID at the venue entrance.</li>
                    <li>This ticket is non-transferable and non-refundable unless the event is cancelled.</li>
                    <li>Entry may be refused if the ticket shows signs of tampering or duplication.</li>
                    <li>Photography, video recording, and audio recording may be prohibited during the event.</li>
                    <li>The organizer reserves the right to add, withdraw, or substitute artists and/or vary the program.</li>
                    <li>Gates open 1 hour before the event start time. Please arrive early to avoid queues.</li>
                    <li>Prohibited items include weapons, illegal substances, outside food/beverages, and professional cameras.</li>
                </ul>
            </div>
            
            <div class="footer">
                <p>For support and inquiries: support@eventticket.com | +62-21-1234-5678</p>
                <p>© 2025 Event Ticket Company. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>

<?php
// STEP 5: Create Form View (Optional)
// resources/views/tickets/form.blade.php
?>
<!DOCTYPE html>
<html>
<head>
    <title>Generate E-Ticket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>E-Ticket Generator</h2>
        <form action="{{ route('ticket.download') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Event Name</label>
                    <input type="text" name="eventName" class="form-control" value="Summer Music Festival 2025">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Event Date</label>
                    <input type="text" name="eventDate" class="form-control" value="November 15, 2025">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Event Time</label>
                    <input type="text" name="eventTime" class="form-control" value="19:00 - 23:00">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Venue</label>
                    <input type="text" name="venue" class="form-control" value="Grand Arena Stadium">
                </div>
                <div class="col-md-12 mb-3">
                    <label>Venue Address</label>
                    <input type="text" name="venueAddress" class="form-control" value="Jl. Sudirman No. 123, Jakarta, Indonesia">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Customer Name</label>
                    <input type="text" name="customerName" class="form-control" value="John Doe">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Customer Email</label>
                    <input type="email" name="customerEmail" class="form-control" value="john.doe@email.com">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Ticket Number</label>
                    <input type="text" name="ticketNumber" class="form-control" value="TKT-2025-001234">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Ticket Type</label>
                    <input type="text" name="ticketType" class="form-control" value="VIP Admission">
                </div>
                <div class="col-md-4 mb-3">
                    <label>Seat Section</label>
                    <input type="text" name="seatSection" class="form-control" value="VIP Section A">
                </div>
                <div class="col-md-4 mb-3">
                    <label>Seat Row</label>
                    <input type="text" name="seatRow" class="form-control" value="Row 5">
                </div>
                <div class="col-md-4 mb-3">
                    <label>Seat Number</label>
                    <input type="text" name="seatNumber" class="form-control" value="Seat 12">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Price</label>
                    <input type="text" name="price" class="form-control" value="Rp 750,000">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Order Date</label>
                    <input type="text" name="orderDate" class="form-control" value="October 21, 2025">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Order ID</label>
                    <input type="text" name="orderId" class="form-control" value="ORD-987654321">
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-lg">Download PDF Ticket</button>
        </form>
    </div>
</body>
</html>