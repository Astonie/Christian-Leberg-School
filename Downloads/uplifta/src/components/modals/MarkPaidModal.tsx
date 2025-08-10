"use client";

import { useForm } from "react-hook-form";
import { z } from "zod";
import { zodResolver } from "@hookform/resolvers/zod";
import { Modal, ModalContent, ModalTrigger, ModalClose } from "@/components/ui/modal";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { FormField } from "@/components/ui/form";

const schema = z.object({ reference: z.string().min(3) });
type Values = z.infer<typeof schema>;

export function MarkPaidModal({ onSubmit, trigger }: { onSubmit?: (v: Values) => Promise<void> | void; trigger?: React.ReactNode }) {
  const form = useForm<Values>({ resolver: zodResolver(schema) });
  const handleSubmit = form.handleSubmit(async (values) => {
    await onSubmit?.(values);
    form.reset({ reference: "" });
  });
  return (
    <Modal>
      <ModalTrigger asChild>{trigger || <Button size="sm">Mark as Paid</Button>}</ModalTrigger>
      <ModalContent>
        <div className="space-y-4">
          <h2 className="text-lg font-semibold">Payment Reference</h2>
          <form onSubmit={handleSubmit} className="space-y-4">
            <FormField label="Reference" error={form.formState.errors.reference?.message}>
              <Input placeholder="Enter reference" {...form.register("reference")} />
            </FormField>
            <div className="flex justify-end gap-2">
              <ModalClose asChild>
                <Button type="button" variant="outline">Cancel</Button>
              </ModalClose>
              <Button type="submit">Save</Button>
            </div>
          </form>
        </div>
      </ModalContent>
    </Modal>
  );
}


