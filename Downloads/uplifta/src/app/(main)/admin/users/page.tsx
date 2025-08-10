"use client";

import { PageHeader } from "@/components/ui/page-header";
import { DataTable } from "@/components/data/DataTable";
import { ColumnDef } from "@tanstack/react-table";
import { useUsers, useRoles } from "@/hooks/useUsers";

type Row = { id: string; name: string; email: string; role: string };
const columns: ColumnDef<Row>[] = [
  { accessorKey: "name", header: "Name" },
  { accessorKey: "email", header: "Email" },
  { accessorKey: "role", header: "Role" },
];

export default function AdminUsersPage() {
  const { data: users = [], isLoading } = useUsers();
  const { data: roles = [] } = useRoles();
  const rows: Row[] = users.map((u) => ({ id: u.id, name: u.name, email: u.email, role: roles.find((r) => r.id === u.roleId)?.name || "-" }));
  return (
    <div className="space-y-4">
      <PageHeader title="Users" subtitle="Manage system users" />
      <DataTable columns={columns} data={rows} isLoading={isLoading} enableColumnFilters />
    </div>
  );
}


