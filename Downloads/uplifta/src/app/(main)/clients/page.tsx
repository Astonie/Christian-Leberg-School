"use client";

import { PageHeader } from "@/components/ui/page-header";
import { Button } from "@/components/ui/button";
import { DataTable } from "@/components/data/DataTable";
import { ColumnDef } from "@tanstack/react-table";
import { useClients } from "@/hooks/useClients";
import { ExportButtons } from "@/components/export/ExportButtons";

type Row = {
  id: string;
  name: string;
  phone: string;
  loansCount: number;
};

const columns: ColumnDef<Row>[] = [
  { accessorKey: "id", header: "ID" },
  { accessorKey: "name", header: "Name" },
  { accessorKey: "phone", header: "Phone" },
  { accessorKey: "loansCount", header: "Loans" },
];

export default function ClientsListPage() {
  const { data = [], isLoading } = useClients();
  const rows: Row[] = data.map((c) => ({ id: c.id, name: c.name, phone: c.phone, loansCount: c.loansCount }));

  return (
    <div className="space-y-4">
      <PageHeader
        title="Clients"
        subtitle="Search, filter and manage clients"
        actions={
          <div className="flex items-center gap-2">
            <ExportButtons data={rows} fileBase="clients" />
            <Button asChild>
              <a href="/clients/new">Add Client</a>
            </Button>
          </div>
        }
      />
      <DataTable columns={columns} data={rows} isLoading={isLoading} />
    </div>
  );
}


