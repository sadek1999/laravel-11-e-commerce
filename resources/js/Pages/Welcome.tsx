import Navbar from "@/Components/App/Navbar";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { PageProps } from "@/types";
import { Head, Link } from "@inertiajs/react";

export default function Welcome({
  auth,
  laravelVersion,
  phpVersion,
}: PageProps<{ laravelVersion: string; phpVersion: string }>) {
 

  return (
    <>

      <AuthenticatedLayout>

      </AuthenticatedLayout>
    </>
  );
}
