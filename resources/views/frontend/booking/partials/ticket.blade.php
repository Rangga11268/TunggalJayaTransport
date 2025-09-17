<div class="ticket-container flex justify-center my-6">
    <div class="ticket" style="width: 567px; height: 265px; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border: 1px solid #cbd5e1; border-radius: 8px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1); padding: 15px; font-family: 'Arial', sans-serif; position: relative; overflow: hidden;">
        <!-- Perforation Top -->
        <div style="position: absolute; top: 0; left: 0; right: 0; height: 10px; background-image: radial-gradient(circle at 50% 50%, transparent 4px, #cbd5e1 4px, #cbd5e1 6px, transparent 6px); background-size: 12px 10px; background-repeat: repeat-x; z-index: 1;"></div>
        
        <!-- Ticket Header -->
        <div style="display: flex; align-items: center; border-bottom: 1px dashed #cbd5e1; padding-bottom: 10px; margin-bottom: 12px;">
            <div style="margin-right: 10px;">
                <div style="font-size: 24px; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: #dbeafe; border-radius: 50%;">🚌</div>
            </div>
            <div style="flex: 1;">
                <h1 style="font-size: 16px; font-weight: bold; color: #1e3a8a; margin: 0; letter-spacing: 0.5px;">TUNGGAL JAYA TRANSPORT</h1>
                <p style="font-size: 10px; color: #64748b; margin: 2px 0 0 0;">Perjalanan Aman dan Nyaman</p>
            </div>
        </div>
        
        <!-- Ticket Body -->
        <div style="margin-bottom: 12px;">
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px;">
                <div style="font-size: 11px;">
                    <label style="display: block; font-weight: bold; color: #475569; margin-bottom: 2px;">Passenger Name</label>
                    <div style="font-size: 12px; font-weight: 600; color: #1e293b; background: #f1f5f9; padding: 4px 6px; border-radius: 4px;">{{ $passengerName }}</div>
                </div>
                
                <div style="font-size: 11px;">
                    <label style="display: block; font-weight: bold; color: #475569; margin-bottom: 2px;">Booking Code</label>
                    <div style="font-size: 12px; font-weight: 600; color: #1e293b; background: #f1f5f9; padding: 4px 6px; border-radius: 4px;">{{ $bookingCode }}</div>
                </div>
                
                <div style="font-size: 11px;">
                    <label style="display: block; font-weight: bold; color: #475569; margin-bottom: 2px;">Route</label>
                    <div style="font-size: 12px; font-weight: 600; color: #1e293b; background: #f1f5f9; padding: 4px 6px; border-radius: 4px;">{{ $origin }} → {{ $destination }}</div>
                </div>
                
                <div style="font-size: 11px;">
                    <label style="display: block; font-weight: bold; color: #475569; margin-bottom: 2px;">Departure</label>
                    <div style="font-size: 12px; font-weight: 600; color: #1e293b; background: #f1f5f9; padding: 4px 6px; border-radius: 4px;">{{ $departureDate }} | {{ $departureTime }}</div>
                </div>
                
                <div style="font-size: 11px;">
                    <label style="display: block; font-weight: bold; color: #475569; margin-bottom: 2px;">Seat Number</label>
                    <div style="font-size: 14px; font-weight: 600; color: #1e40af; background: #dbeafe; padding: 4px 6px; border-radius: 4px;">{{ $seatNumber }}</div>
                </div>
                
                <div style="font-size: 11px;">
                    <label style="display: block; font-weight: bold; color: #475569; margin-bottom: 2px;">Bus Type</label>
                    <div style="font-size: 12px; font-weight: 600; color: #1e293b; background: #f1f5f9; padding: 4px 6px; border-radius: 4px;">{{ $busType }}</div>
                </div>
                
                <div style="font-size: 11px;">
                    <label style="display: block; font-weight: bold; color: #475569; margin-bottom: 2px;">Price</label>
                    <div style="font-size: 14px; font-weight: 600; color: #dc2626; background: #fef2f2; padding: 4px 6px; border-radius: 4px;">{{ $price }}</div>
                </div>
                
                <div style="font-size: 11px;">
                    <label style="display: block; font-weight: bold; color: #475569; margin-bottom: 2px;">Boarding Point</label>
                    <div style="font-size: 12px; font-weight: 600; color: #1e293b; background: #f1f5f9; padding: 4px 6px; border-radius: 4px;">{{ $boardingPoint }}</div>
                </div>
            </div>
        </div>
        
        <!-- Ticket Footer -->
        <div style="border-top: 1px dashed #cbd5e1; padding-top: 12px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                <div style="flex: 1; padding: 8px; background: white; border: 1px solid #cbd5e1; border-radius: 4px; margin-right: 10px;">
                    <!-- Barcode will be generated here -->
                    <div style="display: flex; align-items: flex-end; height: 30px; margin-bottom: 4px; justify-content: center;">
                        @for ($i = 0; $i < 30; $i++)
                            <div style="width: 2px; background: #1e293b; margin: 0 0.5px; height: {{ rand(15, 45) }}px;"></div>
                        @endfor
                    </div>
                    <div style="font-size: 10px; font-family: 'Courier New', monospace; letter-spacing: 1px; text-align: center;">{{ $bookingCode }}</div>
                </div>
                
                <!-- QR Code Placeholder -->
                <div style="width: 80px; padding: 5px; background: white; border: 1px solid #cbd5e1; border-radius: 4px; display: flex; flex-direction: column; align-items: center;">
                    <div style="display: inline-block; border: 1px solid #94a3b8; padding: 2px;">
                        @for ($row = 0; $row < 25; $row++)
                            <div style="display: flex;">
                                @for ($col = 0; $col < 25; $col++)
                                    <div style="width: 2px; height: 2px; background: {{ (rand(0, 1) == 1) ? '#0f172a' : '#e2e8f0' }};"></div>
                                @endfor
                            </div>
                        @endfor
                    </div>
                    <div style="font-size: 8px; color: #64748b; margin-top: 4px; text-align: center;">SCAN QR CODE</div>
                </div>
            </div>
            
            <div style="font-size: 9px; color: #64748b; margin-bottom: 8px; line-height: 1.3;">
                <p style="margin: 1px 0;">• Please arrive at least 30 minutes before departure</p>
                <p style="margin: 1px 0;">• Bring this ticket and a valid ID during boarding</p>
                <p style="margin: 1px 0;">• Keep this ticket safe until the end of your journey</p>
            </div>
            
            <div style="text-align: center; font-size: 9px; color: #475569;">
                <p style="margin: 1px 0;">Customer Service: +62 123 456 789</p>
                <p style="margin: 1px 0;">www.tunggaljayatransport.com</p>
            </div>
        </div>
        
        <!-- Perforation Bottom -->
        <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 10px; background-image: radial-gradient(circle at 50% 50%, transparent 4px, #cbd5e1 4px, #cbd5e1 6px, transparent 6px); background-size: 12px 10px; background-repeat: repeat-x; z-index: 1;"></div>
    </div>
</div>