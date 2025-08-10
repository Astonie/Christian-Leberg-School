import { PageHeader } from "@/components/ui/page-header";
import { Button } from "@/components/ui/button";
import { DataTable } from "@/components/data/DataTable";
import { ColumnDef } from "@tanstack/react-table";

type Row = { id: string; applicant: string; amount: number; status: string };
const data: Row[] = [
  { id: "APP-1234", applicant: "Jane Doe", amount: 5_000_000, status: "Pending" },
];
const columns: ColumnDef<Row>[] = [
  { accessorKey: "id", header: "ID" },
  { accessorKey: "applicant", header: "Applicant" },
  { accessorKey: "amount", header: "Amount", cell: ({ row }) => new Intl.NumberFormat("en-UG", { style: "currency", currency: "UGX", maximumFractionDigits: 0 }).format(row.original.amount) },
  { accessorKey: "status", header: "Status" },
];

export default function ApplicationsPage() {
  return (
    <div className="space-y-4">
      <PageHeader
        title="Loan Applications"
        subtitle="Review and manage applications"
        actions={<Button asChild><a href="/applications/new">Create Application</a></Button>}
      />
      <DataTable columns={columns} data={data} />
    </div>
  );
}


