import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Skeleton } from "@/components/ui/skeleton";
import { PageHeader } from "@/components/ui/page-header";
import { AreaSparkline } from "@/components/charts/AreaSparkline";

export default function DashboardPage() {
  return (
    <div className="space-y-6">
      <PageHeader title="Dashboard" subtitle="Portfolio overview and recent performance" />
      <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        {[
          { label: "Total Loans", value: "1,240", data: [2, 3, 2.5, 4, 5, 4.5, 6] },
          { label: "Total Clients", value: "860", data: [1, 1.5, 1.2, 2, 2.2, 2.8, 3.2] },
          { label: "Portfolio at Risk", value: "2.4%", data: [3, 2, 2.4, 2.1, 2.6, 2.3, 2.4] },
          { label: "Overdue", value: "74", data: [1, 0.8, 1.2, 1.1, 1.3, 1.4, 1.2] },
        ].map((kpi) => (
          <Card key={kpi.label}>
            <CardHeader>
              <CardTitle className="text-sm text-muted-foreground">
                {kpi.label}
              </CardTitle>
            </CardHeader>
            <CardContent>
              <div className="text-2xl font-bold">{kpi.value}</div>
              <AreaSparkline data={kpi.data.map((v) => ({ value: v }))} />
            </CardContent>
          </Card>
        ))}
      </div>
      <div className="grid gap-4 md:grid-cols-2">
        <Card>
          <CardHeader>
            <CardTitle>Monthly Disbursements</CardTitle>
          </CardHeader>
          <CardContent>
            <Skeleton className="h-56 w-full" />
          </CardContent>
        </Card>
        <Card>
          <CardHeader>
            <CardTitle>Monthly Repayments</CardTitle>
          </CardHeader>
          <CardContent>
            <Skeleton className="h-56 w-full" />
          </CardContent>
        </Card>
      </div>
      <Card>
        <CardHeader>
          <CardTitle>Overdue Loans</CardTitle>
        </CardHeader>
        <CardContent>
          <div className="text-sm text-muted-foreground">No data</div>
        </CardContent>
      </Card>
    </div>
  );
}


