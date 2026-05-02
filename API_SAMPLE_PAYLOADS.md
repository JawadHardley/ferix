# Ferix API - Sample Application Payloads

## Prerequisites

1. **Login First** to get authentication token:
```bash
POST /api/login
Content-Type: application/json

{
  "email": "olomidereck@hotmail.com",
  "password": "123456"
}
```

Response will include `token`. Use it in Authorization header for subsequent requests:
```
Authorization: Bearer {token}
```

**Login Response Example:**
```json
{
  "user": {
    "id": 2,
    "name": "Dereck OLomi",
    "role": "transporter",
    "email": "olomidereck@hotmail.com",
    "company": 3,
    "email_verified_at": "2026-04-26T11:10:39.000000Z",
    "created_at": "2026-04-26T11:08:22.000000Z"
  },
  "token": "8|CrXa0fpfVc7i1sMBBPoiqW2OgwSAigIgkuzyu0I68c13b8da"
}
```

---

## Regional Application (40+ fields)

**Endpoint:** `POST /api/applications`  
**Content-Type:** `multipart/form-data` (for file uploads) or `application/json` (without files)

### Sample Payload (without files):

```json
{
  "feri_type": "regional",
  "transport_mode": "Road",
  "transporter_company": "Alistair James Company Ltd",
  "entry_border_drc": "Kasumbalesa",
  "truck_details": "Volvo FH16 Registration: KEN-4573-A, Capacity: 25 tons",
  "arrival_station": "Kasumbalesa Border Post",
  "arrival_date": "2026-05-15",
  "final_destination": "Kinshasa, Democratic Republic of Congo",
  "importer_name": "Sulphur Imports DRC Ltd",
  "importer_phone": "+243912345678",
  "importer_email": "purchasing@sulphur-imports.cd",
  "importer_address": "123 Boulevard de l'Indépendance, Kinshasa, DRC",
  "exporter_name": "Global Minerals Trading Corp",
  "exporter_phone": "+1-888-555-0100",
  "exporter_email": "exports@globalmineral.com",
  "exporter_address": "456 Trade Lane, New York, USA",
  "cf_agent": "Congo Freight Solutions",
  "cf_agent_contact": "+243812345678",
  "cargo_description": "Elemental Sulphur (99.9% purity) for industrial applications",
  "hs_code": "250100",
  "package_type": "Bulk (Bagged in 50kg bags)",
  "quantity": 500,
  "weight": 25000,
  "volume": "125 cubic meters",
  "company_ref": "ORD-2026-SULPH-001",
  "cargo_origin": "Kuwait",
  "customs_decl_no": "CUST-2026-045821",
  "manifest_no": "KEN-SHIP-2026-18374",
  "occ_bivac": "OCC-2026-SULPH",
  "instructions": "Handle with care. Ensure proper ventilation. Do not expose to moisture.",
  "fob_currency": "USD",
  "fob_value": 15000,
  "po": "PO-2026-SULPH-15K",
  "incoterm": "CIF",
  "freight_currency": "USD",
  "freight_value": 2500,
  "insurance_currency": "USD",
  "insurance_value": 850,
  "additional_fees_currency": "USD",
  "additional_fees_value": 300,
  "importer_phone": "+243912345678",
  "importer_details": "Established importing company with 10+ years experience",
  "fix_number": "FIX-SULPH-2026-001"
}
```

### Sample Payload (with files) - Form Data:

Use `multipart/form-data` in Postman:

| Field | Type | Value |
|-------|------|-------|
| feri_type | text | regional |
| transport_mode | text | Road |
| transporter_company | text | Alistair James Company Ltd |
| entry_border_drc | text | Kasumbalesa |
| truck_details | text | Volvo FH16 Registration: KEN-4573-A, Capacity: 25 tons |
| arrival_station | text | Kasumbalesa Border Post |
| arrival_date | text | 2026-05-15 |
| final_destination | text | Kinshasa, Democratic Republic of Congo |
| importer_name | text | Sulphur Imports DRC Ltd |
| importer_phone | text | +243912345678 |
| importer_email | text | purchasing@sulphur-imports.cd |
| importer_address | text | 123 Boulevard de l'Indépendance, Kinshasa, DRC |
| exporter_name | text | Global Minerals Trading Corp |
| exporter_phone | text | +1-888-555-0100 |
| exporter_email | text | exports@globalmineral.com |
| exporter_address | text | 456 Trade Lane, New York, USA |
| cf_agent | text | Congo Freight Solutions |
| cf_agent_contact | text | +243812345678 |
| cargo_description | text | Elemental Sulphur (99.9% purity) for industrial applications |
| hs_code | text | 250100 |
| package_type | text | Bulk (Bagged in 50kg bags) |
| quantity | text | 500 |
| weight | text | 25000 |
| volume | text | 125 cubic meters |
| company_ref | text | ORD-2026-SULPH-001 |
| cargo_origin | text | Kuwait |
| customs_decl_no | text | CUST-2026-045821 |
| manifest_no | text | KEN-SHIP-2026-18374 |
| occ_bivac | text | OCC-2026-SULPH |
| instructions | text | Handle with care. Ensure proper ventilation. Do not expose to moisture. |
| fob_currency | text | USD |
| fob_value | text | 15000 |
| po | text | PO-2026-SULPH-15K |
| incoterm | text | CIF |
| freight_currency | text | USD |
| freight_value | text | 2500 |
| insurance_currency | text | USD |
| insurance_value | text | 850 |
| additional_fees_currency | text | USD |
| additional_fees_value | text | 300 |
| invoice | file | (select PDF, DOC, or image file, max 20MB) |
| packing_list | file | (select PDF, DOC, or image file, max 20MB) |
| manifest | file | (select PDF, DOC, or image file, max 20MB) |
| customs | file | (select PDF, DOC, or image file, max 20MB) |

---

## Continuance Application (20 core fields)

**Endpoint:** `POST /api/applications`  
**Content-Type:** `multipart/form-data` or `application/json`

### Sample Payload (without files):

```json
{
  "feri_type": "continuance",
  "company_ref": "ORD-2026-SULPH-001-CONT",
  "po": "PO-2026-SULPH-15K-FOLLOW",
  "validate_feri_cert": "CERT-2026-SULPH-001",
  "entry_border_drc": "Kasumbalesa",
  "arrival_date": "2026-06-20",
  "final_destination": "Lubumbashi, Democratic Republic of Congo",
  "customs_decl_no": "CUST-2026-045822",
  "arrival_station": "Kasumbalesa Border Post",
  "truck_details": "Scania R440 Registration: KEN-2847-B, Capacity: 20 tons",
  "transporter_company": "Alistair James Company Ltd",
  "weight": 18000,
  "quantity": 360,
  "volume": "90 cubic meters",
  "importer_name": "Sulphur Imports DRC Ltd",
  "cf_agent": "Congo Freight Solutions",
  "exporter_name": "Global Minerals Trading Corp",
  "freight_currency": "USD",
  "freight_value": 1800,
  "fob_value": 10800,
  "insurance_value": 600,
  "instructions": "Follow-up shipment for previous CERT-2026-SULPH-001. Same handling requirements."
}
```

### Sample Payload (with files) - Form Data:

| Field | Type | Value |
|-------|------|-------|
| feri_type | text | continuance |
| company_ref | text | ORD-2026-SULPH-001-CONT |
| po | text | PO-2026-SULPH-15K-FOLLOW |
| validate_feri_cert | text | CERT-2026-SULPH-001 |
| entry_border_drc | text | Kasumbalesa |
| arrival_date | text | 2026-06-20 |
| final_destination | text | Lubumbashi, Democratic Republic of Congo |
| customs_decl_no | text | CUST-2026-045822 |
| arrival_station | text | Kasumbalesa Border Post |
| truck_details | text | Scania R440 Registration: KEN-2847-B, Capacity: 20 tons |
| transporter_company | text | Alistair James Company Ltd |
| weight | text | 18000 |
| quantity | text | 360 |
| volume | text | 90 cubic meters |
| importer_name | text | Sulphur Imports DRC Ltd |
| cf_agent | text | Congo Freight Solutions |
| exporter_name | text | Global Minerals Trading Corp |
| freight_currency | text | USD |
| freight_value | text | 1800 |
| fob_value | text | 10800 |
| insurance_value | text | 600 |
| instructions | text | Follow-up shipment for previous CERT-2026-SULPH-001. Same handling requirements. |
| invoice | file | (select file, max 20MB) |
| packing_list | file | (optional) |
| manifest | file | (optional) |
| customs | file | (optional) |

---

## Testing Workflow

### Step 1: Login
```bash
TOKEN=$(curl -s -X POST http://127.0.0.1:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "olomidereck@hotmail.com",
    "password": "123456"
  }' | jq -r '.token')

echo "Token: $TOKEN"
```

Save the `token` from response.

### Step 2: Create Regional Application
```bash
curl -X POST http://127.0.0.1:8000/api/applications \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{ ... regional payload ... }'
```

### Step 3: List Applications
```bash
curl -X GET http://127.0.0.1:8000/api/applications \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Step 4: Get Application Details
```bash
curl -X GET http://127.0.0.1:8000/api/applications/{id} \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Step 5: Get Application Age
```bash
curl -X GET http://127.0.0.1:8000/api/applications/{id}/age \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Step 6: Get Certificate (if available)
```bash
curl -X GET http://127.0.0.1:8000/api/applications/{id}/certificate \
  -H "Authorization: Bearer YOUR_TOKEN" \
  --output certificate.pdf
```

### Step 7: Get Invoice (if available)
```bash
curl -X GET http://127.0.0.1:8000/api/applications/{id}/invoice \
  -H "Authorization: Bearer YOUR_TOKEN" \
  --output invoice.pdf
```

---

## Field Requirements Summary

### Regional (40+ fields)
- **Required:** transport_mode, transporter_company, entry_border_drc, truck_details, arrival_station, arrival_date, final_destination, importer_name, importer_phone, exporter_name, exporter_phone, cf_agent, cf_agent_contact, cargo_description, hs_code, package_type, quantity, weight, volume
- **Optional:** company_ref, cargo_origin, customs_decl_no, manifest_no, occ_bivac, instructions, importer_email, importer_address, exporter_email, exporter_address, importer_details, fix_number, fob_currency, fob_value, po, incoterm, freight_currency, freight_value, insurance_currency, insurance_value, additional_fees_currency, additional_fees_value
- **Files (optional):** invoice, packing_list, manifest, customs (max 20MB each, PDF/DOC/DOCX/JPG/PNG)

### Continuance (20 core fields)
- **Required:** entry_border_drc, arrival_date, final_destination, arrival_station, truck_details, transporter_company, weight, quantity, volume, importer_name, cf_agent, exporter_name
- **Optional:** company_ref, po, validate_feri_cert, customs_decl_no, freight_currency, freight_value, fob_value, insurance_value, instructions
- **Files (optional):** invoice, packing_list, manifest, customs (max 20MB each, PDF/DOC/DOCX/JPG/PNG)

---

## Response Examples

### Success (201 Created)
```json
{
  "message": "Regional application created successfully",
  "data": {
    "id": 1,
    "user_id": 2,
    "feri_type": "regional",
    "status": 1,
    "transport_mode": "Road",
    "transporter_company": "Alistair James Company Ltd",
    "entry_border_drc": "Kasumbalesa",
    "truck_details": "Volvo FH16 Registration: KEN-4573-A, Capacity: 25 tons",
    "arrival_station": "Kasumbalesa Border Post",
    "arrival_date": "2026-05-15",
    "final_destination": "Kinshasa, Democratic Republic of Congo",
    "importer_name": "Sulphur Imports DRC Ltd",
    "importer_phone": "+243912345678",
    "exporter_name": "Global Minerals Trading Corp",
    "exporter_phone": "+1-888-555-0100",
    "cf_agent": "Congo Freight Solutions",
    "cf_agent_contact": "+243812345678",
    "cargo_description": "Elemental Sulphur (99.9% purity) for industrial applications",
    "hs_code": "250100",
    "package_type": "Bulk (Bagged in 50kg bags)",
    "quantity": 500,
    "weight": 25000,
    "volume": "125 cubic meters",
    "documents_upload": "{\"invoice\":\"feri_documents/abc123.pdf\"}",
    "created_at": "2026-04-28T17:56:26.000000Z",
    "updated_at": "2026-04-28T17:56:26.000000Z"
  }
}
```

### Validation Error (422 Unprocessable Entity)
```json
{
  "message": "The arrival date field is required.",
  "errors": {
    "arrival_date": ["The arrival date field is required."]
  }
}
```

### Authentication Error (401 Unauthorized)
```json
{
  "message": "Unauthenticated"
}
```

---

## Notes

1. **Dates:** Always use format `YYYY-MM-DD` and must be >= today
2. **Numbers:** Use numeric types (not strings) for quantity, weight, values
3. **File Uploads:** Use `multipart/form-data` when sending files
4. **Authentication:** Token expires in 24 hours; use token manager scripts for auto-refresh
5. **Email Notifications:** NewAppMail is queued to vendor company when application is created
6. **Status:** New applications always start with status=1
7. **user_id:** Automatically set from authenticated user
