"use client";

import { useState } from "react";
import { useForm } from "react-hook-form";
import { z } from "zod";
import { zodResolver } from "@hookform/resolvers/zod";
import { PageHeader } from "@/components/ui/page-header";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { FormField } from "@/components/ui/form";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs";
import { Select, SelectItem } from "@/components/ui/select";
import { DatePicker } from "@/components/ui/date-picker";
import { useClients } from "@/hooks/useClients";
import { useLoanProducts } from "@/hooks/useLoanProducts";
import { toast } from "sonner";

const schema = z.object({
  clientId: z.string().min(1, "Select a client"),
  productId: z.string().min(1, "Select a product"),
  amount: z.number().min(1000),
  termMonths: z.number().min(1),
  firstRepaymentDate: z.date(),
});
type Values = z.infer<typeof schema>;

export default function NewApplicationPage() {
  const { data: clients = [] } = useClients();
  const { data: products = [] } = useLoanProducts();
  const [step, setStep] = useState(0);
  const form = useForm<Values>({
    resolver: zodResolver(schema),
    defaultValues: { amount: 0, termMonths: 6, firstRepaymentDate: new Date() },
  });

  const next = async () => {
    const ok = await form.trigger(step === 0 ? ["clientId"] : step === 1 ? ["productId", "amount", "termMonths"] : []);
    if (ok) setStep((s) => Math.min(3, s + 1));
  };
  const prev = () => setStep((s) => Math.max(0, s - 1));

  const onSubmit = form.handleSubmit(async () => {
    await new Promise((r) => setTimeout(r, 600));
    toast.success("Application submitted");
  });

  return (
    <div className="space-y-4">
      <PageHeader title="New Loan Application" subtitle="Multi-step application wizard" />
      <Tabs value={String(step)}>
        <TabsList>
          <TabsTrigger value="0">Client</TabsTrigger>
          <TabsTrigger value="1">Loan Details</TabsTrigger>
          <TabsTrigger value="2">Schedule</TabsTrigger>
          <TabsTrigger value="3">Review</TabsTrigger>
        </TabsList>
        <div className="mt-4 rounded-md border p-4">
          <TabsContent value="0">
            <div className="grid gap-4 sm:grid-cols-2">
              <div>
                <label className="mb-2 block text-sm font-medium">Select client</label>
                <div className="flex gap-2">
                  <Select label={clients.find((c) => c.id === form.watch("clientId"))?.name || "Choose client"}>
                    {clients.map((c) => (
                      <SelectItem key={c.id} onSelect={() => form.setValue("clientId", c.id)}>{c.name}</SelectItem>
                    ))}
                  </Select>
                  <Button variant="outline" asChild>
                    <a href="/clients/new">New Client</a>
                  </Button>
                </div>
                {form.formState.errors.clientId && (
                  <p className="mt-2 text-xs text-destructive">{form.formState.errors.clientId.message as string}</p>
                )}
              </div>
            </div>
          </TabsContent>
          <TabsContent value="1">
            <div className="grid gap-4 sm:grid-cols-2">
              <div>
                <label className="mb-2 block text-sm font-medium">Product</label>
                <Select label={products.find((p) => p.id === form.watch("productId"))?.name || "Choose product"}>
                  {products.map((p) => (
                    <SelectItem key={p.id} onSelect={() => form.setValue("productId", p.id)}>
                      {p.name} ({p.interestRateAnnualPct}% p.a.)
                    </SelectItem>
                  ))}
                </Select>
                {form.formState.errors.productId && (
                  <p className="mt-2 text-xs text-destructive">{form.formState.errors.productId.message as string}</p>
                )}
              </div>
              <FormField label="Amount" error={form.formState.errors.amount?.message}>
                <Input type="number" {...form.register("amount", { valueAsNumber: true })} />
              </FormField>
              <FormField label="Term (months)" error={form.formState.errors.termMonths?.message}>
                <Input type="number" {...form.register("termMonths", { valueAsNumber: true })} />
              </FormField>
              <div>
                <label className="mb-2 block text-sm font-medium">First repayment date</label>
                <DatePicker value={form.watch("firstRepaymentDate")} onChange={(d) => form.setValue("firstRepaymentDate", d || new Date())} />
              </div>
            </div>
          </TabsContent>
          <TabsContent value="2">
            <div className="text-sm text-muted-foreground">Schedule preview coming soon.</div>
          </TabsContent>
          <TabsContent value="3">
            <div className="space-y-2 text-sm">
              <div>Client: {clients.find((c) => c.id === form.watch("clientId"))?.name || "-"}</div>
              <div>Product: {products.find((p) => p.id === form.watch("productId"))?.name || "-"}</div>
              <div>Amount: {form.watch("amount")}</div>
              <div>Term: {form.watch("termMonths")} months</div>
              <div>First repayment: {form.watch("firstRepaymentDate")?.toDateString()}</div>
            </div>
          </TabsContent>
        </div>
      </Tabs>

      <div className="flex justify-between">
        <Button variant="outline" onClick={prev} disabled={step === 0}>
          Back
        </Button>
        {step < 3 ? (
          <Button onClick={next}>Next</Button>
        ) : (
          <Button onClick={onSubmit}>Submit Application</Button>
        )}
      </div>
    </div>
  );
}


